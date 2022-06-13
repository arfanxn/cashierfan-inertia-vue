<?php

namespace App\Actions;

use App\Exceptions\Product\OutOfStockException as OutOfStockProductException;
use App\Helpers\Redirect;
use App\Helpers\Str;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MakeSaleAction
{
    public static function exec(
        $cashier,
        $customer = null,
        float $customerPayMoney,
        float $discount = 0,
        array $products = []
    ) {
        try {
            DB::beginTransaction();

            // if customer is null get the "general" customer  (just buyer with no name) 
            $customer = $customer ? $customer : Customer::where("id", 1)->first();

            $sumTax = 0;
            $sumProfit = 0;
            $sumGrossPrice = 0;
            $sumNetPrice = 0;

            $product_sale = [];
            foreach ($products as $product) {
                // get summery for tax,profit, and grossprice
                $sumTax += ($product['tax'] * $product['quantity']);
                $sumProfit += ($product['profit'] * $product['quantity']);
                $sumGrossPrice += ($product['gross_price'] * $product['quantity']);

                // update the product stocks
                $prodQuery = Product::query()->where("id", $product['id']);
                if ($prodQuery->first()->stock ?? 0  >= $product['quantity'])
                    $prodQuery->decrement("stock", $product['quantity']);
                else throw new OutOfStockProductException();

                array_push(
                    $product_sale,
                    collect($product)->only([
                        "tax_percentage",
                        "tax",
                        "profit_percentage",
                        "profit",
                        "quantity",
                        "gross_price",
                        "net_price",
                    ])->merge([
                        "product_id" => $product['id'],
                        "sum_tax" => ($product['tax'] * $product['quantity']),
                        "sum_profit" => ($product['profit'] * $product['quantity']),
                        "sum_gross_price" => ($product['gross_price'] * $product['quantity']),
                        "sum_net_price" => ($product['net_price'] * $product['quantity']),
                    ])->toArray()
                );
            }

            // get the summery of netPrice
            $sumNetPrice = $discount > 0 ?
                // if discount available -> subtract the net_price by discount 
                ($sumTax + $sumProfit + $sumGrossPrice) - $discount
                // otherwise
                : ($sumTax + $sumProfit + $sumGrossPrice);

            // get the customer pay_change_money (get customer change)
            $customerChangeMoney = $customerPayMoney - $sumNetPrice;

            // create the sale
            $insertedSaleID = Sale::insertGetId([
                "invoice" => strtoupper(Str::random()),
                "cashier_id" => $cashier['id'],
                "customer_id" => $customer['id'],
                "customer_pay_money" => $customerPayMoney,
                'customer_change_money' => $customerChangeMoney,
                "discount" => $discount,
                'sum_tax' => $sumTax,
                'sum_profit' => $sumProfit,
                'sum_gross_price' => $sumGrossPrice,
                'sum_net_price' => $sumNetPrice,
                "created_at" => now(),
            ]);
            $sale = Sale::where("id", $insertedSaleID)->first();

            // attach the relation products at sale;
            $product_sale = collect($product_sale)->map(
                fn ($product) => collect($product)->merge(['sale_id' => $insertedSaleID])
            );
            $sale->products()->attach($product_sale);

            DB::commit();

            $sale = collect($sale)->merge(['products' => $product_sale]);
            return  $sale;
        } catch (\Throwable $th) {
            DB::rollBack();

            if (request()->expectsJson()  || request()->wantsJson())
                return response()
                    ->json(['error_message' => $th->getMessage()], 500)
                    ->throwResponse();

            return Redirect::now(
                redirect()->back()->withErrors(['error_message' => $th->getMessage()])
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $data = [
                "error_message" => app()->environment(['local', 'debug'])
                    ? $e->getMessage() : "Something went wrong",
            ];

            if (request()->expectsJson()  || request()->wantsJson())
                return response()->json($data, 500)->throwResponse();

            return Redirect::now(redirect()->back()->withErrors($data));
        }
    }
}

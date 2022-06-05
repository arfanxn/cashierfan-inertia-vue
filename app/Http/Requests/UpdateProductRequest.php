<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "code" => "required|string|min:4|max:50|unique:products,code,code," . $this->code,
            "image" => "required|image|mimes:jpg,jpeg,png,svg,gif,jfif|max:10240",
            "name" => "required|min:2|max:100|string|unique:products,name,name," . $this->name,
            "description" => "nullable|string|max:255",
            "gross_price" => "required|numeric",
            "net_price" => "required|numeric",
            "profit" => "required|numeric",
            "stock" => "required|numeric",
        ];
    }
}

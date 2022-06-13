<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Statistic extends MorphPivot
{
    protected $table = "statistics";
    private $name = "statisticable";

    protected $fillable = [
        "statisticable_type",
        "statisticable_id",
        "key",
        "value",
    ];

    public function bestSellingProducts()
    {
        return $this->morphedByMany(Product::class, $this->name);
    }
}

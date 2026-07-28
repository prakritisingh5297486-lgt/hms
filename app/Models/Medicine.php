<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [

        'medicine_name',

        'medicine_code',

        'category',

        'manufacturer',

        'price',

        'stock',

        'minimum_stock',

        'expiry_date',

    ];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'reserved_quantity',
    ];

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}

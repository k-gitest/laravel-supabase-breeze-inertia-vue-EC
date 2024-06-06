<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function stock(){
        return $this->hasMany(Stock::class);
    }

    public function product()
    {
        return $this->hasManyThrough(Product::class, Stock::class, 'warehouse_id', 'id', 'id', 'product_id');
    }

}

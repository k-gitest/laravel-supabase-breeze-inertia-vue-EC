<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\Vector;
use Pgvector\Laravel\HasNeighbors;

class ProductVector extends Model
{
    use HasFactory, HasNeighbors;

    protected $fillable = [
        'product_id',
        'type',
        'embedding',
    ];

    protected $casts = [
        'embedding' => Vector::class
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

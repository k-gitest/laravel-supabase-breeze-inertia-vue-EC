<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * 配列/JSONシリアル化の日付の準備
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        //return $date->format('Y-m-d-h');
        return $date->format('Y年m月d日h時');
    }
}

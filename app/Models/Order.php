<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_intent_id',
        'status',
        'currency',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y年m月d日h時m分');
    }
}

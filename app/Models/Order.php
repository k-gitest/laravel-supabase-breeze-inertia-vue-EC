<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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

    
    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->format('Y年m月d日H時i分');
    }
    
    
}

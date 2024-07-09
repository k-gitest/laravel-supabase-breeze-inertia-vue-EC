<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DateTimeInterface;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'title',
        'content',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    
    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->format('Y年m月d日H時i分');
    }
    
}

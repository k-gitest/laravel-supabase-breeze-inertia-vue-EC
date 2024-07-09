<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DateTimeInterface;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 
      'user_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->format('Y年m月d日H時i分');
    }
}

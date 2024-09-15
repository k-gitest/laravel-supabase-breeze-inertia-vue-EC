<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\Vector;

class UserVector extends Model
{
    use HasFactory, HasNeighbors;

    protected $fillable = [
        'user_id',
        'type',
        'embedding',
    ];

    protected $casts = [
        'embedding' => Vector::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DateTimeInterface;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'ip_address',
        'user_agent',
        'language',
        'previous_url',
        'referrer',
        'platform',
        'device',
        'browser',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
        ];
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->format('Y年m月d日H時i分');
    }
}

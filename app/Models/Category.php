<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Category extends Model
{
    use HasFactory;

  protected $fillable = [
    'name',
    'description',
  ];

  public function product() {
    return $this->hasMany(Product::class, 'category_id')
      ->orderBy('created_at', 'desc');
  }

  protected function serializeDate(DateTimeInterface $date)
  {
      return $date->format('Y年m月d日h時m分');
  }
}

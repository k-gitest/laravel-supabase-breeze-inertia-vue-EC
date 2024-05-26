<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

  protected $fillable = [
    'name',
    'price_excluding_tax',
    'price_including_tax',
    'description',
    'tax_rate',
    'category_id',
  ];

  public function category() {
    return $this->belongsTo(Category::class);
  }

  public function image() {
    return $this->hasMany(Image::class);
  }

  public function favorite(){
    return $this->hasMany(Favorite::class);
  }

  public function comment(){
    return $this->hasMany(Comment::class);
  }

  public function stock(){
    return $this->hasMany(Stock::class);
  }
  
}

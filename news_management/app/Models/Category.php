<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'product_id']; // Ajoute les champs nécessaires

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description']; // Mets les champs nécessaires

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}

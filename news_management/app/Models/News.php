<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'short_description', 'content', 'image', 
        'category_id', 'product_id', 'author_id', 'published_at'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Exemple : Récupérer un produit existant pour l'associer aux catégories
        $product = Product::first(); // ou Product::find(1);

        Category::create([
            'name' => 'Catégorie A',
            'product_id' => $product->id,
        ]);

        Category::create([
            'name' => 'Catégorie B',
            'product_id' => $product->id,
        ]);
    }
}

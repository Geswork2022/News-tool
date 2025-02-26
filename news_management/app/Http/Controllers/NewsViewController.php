<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Pour Str::slug()
use App\Models\News;
use App\Models\Category;
use App\Models\Product;

class NewsViewController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        $query = News::query();
        // Filtre par recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%");
            });
        }
        // Filtre par produit
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        $news = $query->get();
        return view('news.index', compact('news', 'products'));
    }

    public function create()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('news.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required',
            'short_description' => 'required',
            'content'           => 'required',
            'category_id'       => 'nullable|exists:categories,id',
            'product_id'        => 'nullable|exists:products,id',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Gestion de l'image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        // Génération d'un slug unique pour éviter la contrainte d'unicité
        $slugBase = Str::slug($request->title);
        $slug     = $slugBase;
        $counter  = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        News::create([
            'title'             => $request->title,
            'slug'              => $slug,
            'short_description' => $request->short_description,
            'content'           => $request->content,
            'category_id'       => $request->category_id,
            'product_id'        => $request->product_id,
            'image'             => $imagePath
        ]);

        return redirect()->route('news.index')->with('success', 'News ajoutée avec succès !');
    }

    public function show($id)
    {
        // Charger la relation 'product' pour pouvoir faire $news->product->name
        $news = News::with('product.categories')->findOrFail($id);
        return view('news.show', compact('news'));
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = Category::all();
        // Récupérer aussi les produits pour pouvoir modifier le produit associé
        $products = Product::all();

        return view('news.edit', compact('news', 'categories', 'products'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title'             => 'required',
            'short_description' => 'required',
            'content'           => 'required',
            'category_id'       => 'nullable|exists:categories,id',
            'product_id'        => 'nullable|exists:products,id',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Vérifier si le titre a changé => regénérer le slug si besoin
        if ($news->title !== $request->title) {
            $slugBase = Str::slug($request->title);
            $slug     = $slugBase;
            $counter  = 1;

            while (News::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }
            $news->slug = $slug;
        }

        // Mettre à jour les champs
        $news->title             = $request->title;
        $news->short_description = $request->short_description;
        $news->content           = $request->content;
        $news->category_id       = $request->category_id;
        $news->product_id        = $request->product_id;

        // Gérer l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si existante
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $news->image = $request->file('image')->store('news_images', 'public');
        }

        $news->save();

        return redirect()->route('news.index')->with('success', 'News mise à jour avec succès!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();

        return redirect()->route('news.index')->with('success', 'News supprimée avec succès!');
    }

    public function uploadAttachment(Request $request)
    {
        \Log::info('✅ Upload CKEditor appelé !');

        if (!$request->hasFile('upload')) {
            \Log::error('❌ Aucun fichier reçu pour l\'upload via CKEditor');
            return response()->json(['error' => 'Aucun fichier trouvé'], 400);
        }

        $file = $request->file('upload');
        \Log::info('📂 Fichier reçu : ' . $file->getClientOriginalName());

        // Validation du fichier
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Créer le dossier "uploads" s'il n'existe pas
            if (!Storage::disk('public')->exists('uploads')) {
                \Log::warning('⚠️ Le dossier "uploads" n\'existe pas, tentative de création...');
                Storage::disk('public')->makeDirectory('uploads');
            }

            // Stocker l'image
            $path = $file->store('uploads', 'public');
            \Log::info('✅ Image stockée à : ' . $path);

            if (!Storage::disk('public')->exists($path)) {
                \Log::error('❌ Fichier introuvable après stockage : ' . $path);
                return response()->json(['error' => 'Erreur de stockage'], 500);
            }

            // Générer l'URL
            $url = asset('storage/' . $path);
            \Log::info('🌍 URL de l\'image : ' . $url);

            return response()->json(['url' => $url]);
        } catch (\Exception $e) {
            \Log::error('❌ Erreur pendant l\'upload : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur, impossible de stocker le fichier'], 500);
        }
    }

    public function newsByProduct($productId)
    {
        // 1. Récupérer le produit
        $product = Product::findOrFail($productId);

        // 2. Récupérer les news associées
        $news = News::where('product_id', $productId)->get();

        // 3. Retourner une vue (par ex. news.by_product) avec ces données
        return view('news.by_product', compact('product', 'news'));
    }

    public function showProduct($id)
    {
        // Récupère le produit avec ses catégories via eager loading
        $product = Product::with('categories')->findOrFail($id);
        return view('products.show', compact('product'));
    }
}

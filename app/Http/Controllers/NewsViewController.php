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
        $categories = Category::all();
        $query = News::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%");
            });
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $news = $query->get();
        return view('news.index', compact('news', 'products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $products = Product::all();
        return view('news.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        \Log::info('✅ Début de la création d\'une news.');

        $request->validate([
            'title'             => 'required',
            'short_description' => 'required',
            'content'           => 'required',
            'category_id'       => 'nullable|exists:categories,id',
            'product_id'        => 'nullable|exists:products,id',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'promotional_message' => 'nullable',
        ]);

        \Log::info('📋 Données validées : ', $request->all());

        $imagePath = null;
        if ($request->hasFile('image')) {
            \Log::info('📂 Image reçue : ' . $request->file('image')->getClientOriginalName());
            $imagePath = $request->file('image')->store('news_images', 'public');
            \Log::info('✅ Image stockée à : ' . $imagePath);
        }

        $slugBase = Str::slug($request->title);
        $slug     = $slugBase;
        $counter  = 1;

        while (News::where('slug', $slug)->exists()) {
            \Log::warning('⚠️ Slug déjà existant : ' . $slug);
            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        \Log::info('✅ Slug généré : ' . $slug);

        try {
            News::create([
                'title'             => $request->title,
                'slug'              => $slug,
                'short_description' => $request->short_description,
                'content'           => $request->content,
                'category_id'       => $request->category_id,
                'product_id'        => $request->product_id,
                'image'             => $imagePath,
                'promotional_message' => $request->promotional_message,
            ]);

            \Log::info('✅ News créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('❌ Erreur lors de la création de la news : ' . $e->getMessage());
            return redirect()->route('news.index')->with('error', 'Erreur lors de l\'ajout de la news.');
        }

        return redirect()->route('news.index')->with('success', 'News ajoutée avec succès !');
    }

    public function show($id)
    {
        $news = News::with('product.categories')->findOrFail($id);
        return view('news.show', compact('news'));
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = Category::all();
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
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'promotional_message' => 'nullable',
        ]);

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

        $news->title             = $request->title;
        $news->short_description = $request->short_description;
        $news->content           = $request->content;
        $news->category_id       = $request->category_id;
        $news->product_id        = $request->product_id;
        $news->promotional_message = $request->promotional_message;

        if ($request->hasFile('image')) {
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

        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if (!Storage::disk('public')->exists('uploads')) {
                \Log::warning('⚠️ Le dossier "uploads" n\'existe pas, tentative de création...');
                Storage::disk('public')->makeDirectory('uploads');
            }
            $path = $file->store('uploads', 'public');
            \Log::info('✅ Image stockée à : ' . $path);

            if (!Storage::disk('public')->exists($path)) {
                \Log::error('❌ Fichier introuvable après stockage : ' . $path);
                return response()->json(['error' => 'Erreur de stockage'], 500);
            }
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
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Produit non trouvé.'], 404);
        }
        $news = News::where('product_id', $productId)->get();
        if ($news->isEmpty()) {
            return response()->json(['message' => 'Aucune news trouvée pour ce produit.'], 404);
        }
        return response()->json([
            'product' => $product,
            'news' => $news
        ]);
    }

    public function showProduct($id)
    {
        $product = Product::with('categories')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function newsByCategory($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Catégorie non trouvée.'], 404);
        }
        $news = News::where('category_id', $categoryId)->get();

        if ($news->isEmpty()) {
            return response()->json(['message' => 'Aucune news trouvée pour cette catégorie.'], 404);
        }
        return response()->json([
            'category' => $category,
            'news' => $news
        ]);
    }
}

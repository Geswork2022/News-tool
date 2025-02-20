<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\News;
use App\Models\Category;
use App\Models\Product;

class NewsViewController extends Controller
{
    public function index()
    {
        $news = News::all();
        return view('news.index', compact('news'));
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

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        News::create([
            'title'             => $request->title,
            'slug'              => strtolower(str_replace(' ', '-', $request->title)),
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
        $news = News::findOrFail($id);
        return view('news.show', compact('news'));
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
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

        $news->title             = $request->title;
        $news->short_description = $request->short_description;
        $news->content           = $request->content;
        $news->category_id       = $request->category_id;
        $news->product_id        = $request->product_id;

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

    /**
     * 🔹 Upload de fichiers via Trix Editor
     */
    public function uploadTrixFile(Request $request)
{
    \Log::info('✅ Upload Trix appelé !');

    // Vérifier si un fichier est reçu
    if (!$request->hasFile('file')) {
        \Log::error('❌ Aucun fichier reçu pour l\'upload via Trix');
        return response()->json(['error' => 'Aucun fichier trouvé'], 400);
    }

    // Récupération du fichier
    $file = $request->file('file');
    \Log::info('📂 Fichier reçu : ' . $file->getClientOriginalName());

    // Validation du fichier
    $request->validate([
        'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        // Vérifier que Laravel peut écrire dans le stockage
        if (!Storage::disk('public')->exists('uploads')) {
            \Log::warning('⚠️ Le dossier "uploads" n\'existe pas, tentative de création...');
            Storage::disk('public')->makeDirectory('uploads');
        }

        // Stocker l'image dans "storage/app/public/uploads"
        $path = $file->store('uploads', 'public');
        \Log::info('✅ Image stockée à : ' . $path);

        // Vérifier si le fichier est bien enregistré
        if (!Storage::disk('public')->exists($path)) {
            \Log::error('❌ Fichier introuvable après stockage : ' . $path);
            return response()->json(['error' => 'Erreur de stockage'], 500);
        }

        // Générer l'URL de l'image
        $url = asset('storage/' . $path);
        \Log::info('🌍 URL de l\'image : ' . $url);

        return response()->json(['url' => $url]);
    } catch (\Exception $e) {
        \Log::error('❌ Erreur pendant l\'upload : ' . $e->getMessage());
        return response()->json(['error' => 'Erreur serveur, impossible de stocker le fichier'], 500);
    }
}

}
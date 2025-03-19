<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();
        if($request->has('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }
        $news = $query->get();
        foreach ($news as $new) {
            $new->short_content = substr($new->content, 0, 100);
            $new->full_url = "https://intranet.geswork.fr/storage/".$new->image;
        }
        return response()->json($news);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);
        $news->full_url = !empty($news->image)
            ? "https://intranet.geswork.fr/storage/" . $news->image
            : null;
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:news,slug',
            'short_description' => 'required|string',
            'content' => 'required',
            'promotional_message' => 'nullable|string',
            'image' => 'nullable|string',
            'product_id' => 'required|integer',
        ]);

        $news = News::create($validated);
        return response()->json($news, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:news,slug,' . $news->id,
            'short_description' => 'sometimes|string',
            'content' => 'sometimes',
            'image' => 'nullable|string',
            'product_id' => 'sometimes|integer',
        ]);

        $news->update($validated);
        return response()->json($news);
    }

    public function destroy($id)
    {
        News::destroy($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function showBySlug($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $news->full_url = !empty($news->image)
            ? "https://intranet.geswork.fr/storage/" . $news->image
            : null;
        return response()->json($news);
    }

    public function create()
    {
        $categories = Category::all(); // Récupère toutes les catégories
        $products = Product::all(); // Récupère tous les produits
        return view('news.create', compact('categories', 'products'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::all();
        foreach ($news as $new) {
            $new->short_content = substr($new->content, 0, 100);
        }
        return response()->json($news);
    }

    public function show($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:news,slug',
            'short_description' => 'required|string',
            'content' => 'required',
            'image' => 'nullable|string',
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
        ]);

        $news->update($validated);
        return response()->json($news);
    }

    public function destroy($id)
    {
        News::destroy($id);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

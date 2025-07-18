<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Services\GeneratorService;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('content', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Order by published_at and created_at
        $articles = $query->orderBy('published_at', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->appends($request->query());
        
        return view('pages.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('pages.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360', // 15MB
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
        ], [
            'title.required' => 'Title is required',
            'title.max' => 'Title cannot exceed 255 characters',
            'content.required' => 'Content is required',
            'meta_title.max' => 'Meta title cannot exceed 255 characters',
            'meta_description.max' => 'Meta description cannot exceed 500 characters',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters',
            'image.image' => 'File must be an image',
            'image.max' => 'Image size cannot exceed 15MB',
            'published_at.date' => 'Published date must be a valid date',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ]);

        try {
            // Generate slug
            $slug = GeneratorService::generateSlug(new Article(), $request->title);

            // Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = ImageService::uploadAndCompress(
                    $request->file('image'), 
                    'articles', 
                    85
                );
            }

            if ($request->has('is_featured') && $request->is_featured && $request->status !== 'published') {
                return redirect()->back()
                               ->withInput()
                               ->withErrors(['is_featured' => 'Article must be published to be featured.']);
            }
            
            if ($request->has('is_featured') && $request->is_featured && $request->status === 'published') {
                Article::where('is_featured', true)->update(['is_featured' => false]);
            }
            
            $publishedAt = null;
            $isFeatured = false;
            
            if ($request->status === 'published') {
                $publishedAt = $request->published_at ? $request->published_at : now();
                $isFeatured = $request->has('is_featured') && $request->is_featured;
            }
            
            Article::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'image_path' => $imagePath,
                'status' => $request->status,
                'published_at' => $publishedAt,
                'is_featured' => $isFeatured,
            ]);

            return redirect()->route('articles.index')
                           ->with('success', 'Article created successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create article: ' . $e->getMessage());
        }
    }

    public function edit(Article $article)
    {
        return view('pages.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:15360',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
        ], [
            'title.required' => 'Title is required',
            'title.max' => 'Title cannot exceed 255 characters',
            'content.required' => 'Content is required',
            'meta_title.max' => 'Meta title cannot exceed 255 characters',
            'meta_description.max' => 'Meta description cannot exceed 500 characters',
            'meta_keywords.max' => 'Meta keywords cannot exceed 255 characters',
            'image.image' => 'File must be an image',
            'image.max' => 'Image size cannot exceed 15MB',
            'published_at.date' => 'Published date must be a valid date',
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status selected',
        ]);

        try {
            // Generate slug if title changed
            $slug = GeneratorService::generateSlug(new Article(), $request->title, $article->id);

            // Update image if new file provided
            $imagePath = ImageService::updateImage(
                $request->file('image'),
                $article->image_path,
                'articles',
                85
            );

            if ($request->has('is_featured') && $request->is_featured && $request->status !== 'published') {
                return redirect()->back()
                               ->withInput()
                               ->withErrors(['is_featured' => 'Article must be published to be featured.']);
            }
            
            if ($request->has('is_featured') && $request->is_featured && $request->status === 'published') {
                Article::where('is_featured', true)
                       ->where('id', '!=', $article->id)
                       ->update(['is_featured' => false]);
            }
            
            $publishedAt = $article->published_at;
            $isFeatured = false;
            
            if ($request->status === 'published') {
                if (!$publishedAt || $request->published_at) {
                    $publishedAt = $request->published_at ? $request->published_at : now();
                }
                $isFeatured = $request->has('is_featured') && $request->is_featured;
            } else {
                $publishedAt = null;
                $isFeatured = false;
            }
            
            $article->update([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'image_path' => $imagePath,
                'status' => $request->status,
                'published_at' => $publishedAt,
                'is_featured' => $isFeatured,
            ]);

            return redirect()->back()
                           ->with('success', 'Article updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update article: ' . $e->getMessage());
        }
    }

    public function destroy(Article $article)
    {
        try {
            // Delete image if exists
            if ($article->image_path) {
                ImageService::deleteFile($article->image_path);
            }

            $article->delete();

            return redirect()->route('articles.index')
                           ->with('success', 'Article deleted successfully');

        } catch (\Exception $e) {
            return redirect()->route('articles.index')
                           ->with('error', 'Failed to delete article: ' . $e->getMessage());
        }
    }
}
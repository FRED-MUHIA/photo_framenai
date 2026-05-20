<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::query()->latest()->paginate(15);

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog-posts.create', ['post' => new BlogPost(['is_published' => true])]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        BlogPost::query()->create([
            ...$this->mapPayload($validated),
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
            'author_id' => auth()->id(),
        ]);

        return redirect()->route('admin.blog-posts.index')->with('status', 'Blog post created.');
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts.edit', ['post' => $blogPost]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $this->validatePayload($request, $blogPost->id);

        $blogPost->update([
            ...$this->mapPayload($validated),
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
        ]);

        return redirect()->route('admin.blog-posts.index')->with('status', 'Blog post updated.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        return back()->with('status', 'Blog post deleted.');
    }

    private function validatePayload(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', 'unique:blog_posts,slug,' . $id],
            'excerpt' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function mapPayload(array $validated): array
    {
        return [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'] ?? null,
            'cover_image' => $validated['cover_image'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'is_published' => ! empty($validated['is_published']),
            'published_at' => $validated['published_at'] ?? null,
        ];
    }
}

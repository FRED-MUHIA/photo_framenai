<div class="grid gap-4 sm:grid-cols-2">
    <input type="text" name="title" value="{{ old('title', $post->title) }}" placeholder="Post title" class="rounded-xl border-slate-300" required>
    <input type="text" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="Slug (optional)" class="rounded-xl border-slate-300">
    <input type="text" name="cover_image" value="{{ old('cover_image', $post->cover_image) }}" placeholder="Cover image URL" class="rounded-xl border-slate-300 sm:col-span-2">
    <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" placeholder="Meta title" class="rounded-xl border-slate-300">
    <input type="text" name="meta_description" value="{{ old('meta_description', $post->meta_description) }}" placeholder="Meta description" class="rounded-xl border-slate-300">
    <label class="inline-flex items-center gap-2 text-sm">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? true))>
        Published
    </label>
    <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="rounded-xl border-slate-300">
</div>

<textarea name="excerpt" rows="3" class="w-full rounded-xl border-slate-300" placeholder="Short excerpt">{{ old('excerpt', $post->excerpt) }}</textarea>
<textarea name="content" rows="14" class="w-full rounded-xl border-slate-300" placeholder="Blog content (HTML allowed)">{{ old('content', $post->content) }}</textarea>

<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\GetPostsAction;
use App\Actions\Post\UpdatePostAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Menampilkan daftar berita
     */
    public function index(GetPostsAction $action): View
    {
        $posts = $action->execute();

        return view('tu.posts.index', compact('posts'));
    }

    /**
     * Menampilkan form tulis berita baru
     */
    public function create(): View
    {
        return view('tu.posts.create');
    }

    /**
     * Menerbitkan berita baru
     */
    public function store(
        StorePostRequest $request,
        CreatePostAction $action,
    ): RedirectResponse {
        $action->execute($request->validated(), $request->file('image'));

        return redirect()
            ->route('tu.posts.index')
            ->with('success', 'Berita berhasil diterbitkan!');
    }

    /**
     * Menampilkan detail / preview berita
     */
    public function show(Request $request, Post $post): View|RedirectResponse|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json($post);
        }

        return redirect()->route('public.berita.show', $post->slug);
    }

    /**
     * Menampilkan form edit berita
     */
    public function edit(Post $post): View
    {
        return view('tu.posts.edit', compact('post'));
    }

    /**
     * Memperbarui data berita
     */
    public function update(
        UpdatePostRequest $request,
        Post $post,
        UpdatePostAction $action,
    ): RedirectResponse {
        $action->execute($post, $request->validated(), $request->file('image'));

        return redirect()
            ->route('tu.posts.index')
            ->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Upload gambar inline dari teks editor (Summernote)
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $file = $request->file('image');
        $path = $file->store('posts/content', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ]);
    }

    /**
     * Menghapus berita
     */
    public function destroy(
        Post $post,
        DeletePostAction $action,
    ): RedirectResponse {
        $action->execute($post);

        return back()->with('success', 'Berita berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Post\CreatePostAction;
use App\Actions\Post\DeletePostAction;
use App\Actions\Post\GetPostsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
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

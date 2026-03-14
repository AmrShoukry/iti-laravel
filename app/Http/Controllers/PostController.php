<?php

namespace App\Http\Controllers;

use App\Http\Responses\PostDetailsResponse;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index() {
        $posts = Post::withTrashed()->paginate(10);

        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function show($id) {

        $post = Post::with('comments.user')->findOrFail($id);

        return view('posts.show', [
            'post'=> $post
        ]);
    }

    public function create() {

        $users = User::all();

        return view('posts.create', [
            'users' => $users
        ]);
    }

    public function store() {
        $title = request('title');
        $user_id = request('user_id');
        $description = request('description');

        Post::create([
            'title' => $title,
            'description' => $description,
            'user_id' => $user_id
        ]);

        return to_route('posts.index');
    }

    public function edit($id) {
        $post = Post::findOrFail($id);
        $users = User::all();

        return view('posts.edit', [
            'post' => $post,
            'users' => $users
        ]);
    }

    public function update($id) {
        $post = Post::findOrFail($id);

        $post->update([
            'title' => request('title'),
            'description' => request('description'),
            'user_id' => request('user_id')
        ]);

        return to_route('posts.index');
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        return to_route('posts.index');
    }

    public function getDetails($id) {
        $post = Post::findOrFail($id);
        return new PostDetailsResponse($post);
    }


    public function getPosts() {
        $posts = Post::withTrashed()->with(['user', 'comments.user'])->paginate(10);
        $users = User::all();

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'users' => $users,
        ]);
    }

    public function toggleSoftDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        if ($post->trashed()) {
            $post->restore();
        } else {
            $post->delete();
        }

        return back();
    }
}

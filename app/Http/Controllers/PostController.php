<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PostEnhancerAgent;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use App\Http\Responses\PostDetailsResponse;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
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

    public function store(StorePostRequest $request) {

        $data = $request->validated();
        $tags = explode(',', $data['tags']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
        }

        $post = Post::create($data);
        $post->attachTags($tags);
        // Post::create($request->validated());
        // Post::create($request->only(['title', 'description', 'user_id']));

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

    public function update(StorePostRequest $request, $id) {
        $post = Post::findOrFail($id);

        $oldImage = $request->oldImage;

        $data = $request->validated();


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $data['image'] = $path;
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);

            }
        }


        $post->update($data);
        $post->syncTags($data['tags']);

        return to_route('posts.index');
    }

    public function destroy($id) {
        $post = Post::withTrashed()->findOrFail($id);
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->forceDelete();

        return back();
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

    public function restorePosts()
    {
        Post::onlyTrashed()->restore();

        return back();
    }

    public function enhance(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'tags' => 'nullable|array',
        ]);

        // Convert tags array to comma-separated string if needed, or use as array
        $tags = $request->tags ?? [];

        $agent = new PostEnhancerAgent();

        // Use your agent to enhance the post
        $result = $agent->enhance(
            $request->title,
            $request->description,
            $tags
        );

        return response()->json($result);
    }


}

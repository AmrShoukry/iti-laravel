<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store($postId)
    {
        $post = Post::findOrFail($postId);

        $post->comments()->create([
            'body' => request('body'),
        ]);

        return back();
    }

    public function update($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $comment->update([
            'body' => request('body'),
        ]);

        return back();
    }

    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        $comment->delete();

        return back();
    }
}

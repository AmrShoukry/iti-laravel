<?php


namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class PostDetailsResponse implements Responsable
{
    private $post;

    public function __construct($post)
    {
        $this->post = $post;
    }

    public function toResponse($request)
    {
        return response()->json([
            'post' => [
                'id' => $this->post->id,
                'title' => $this->post->title,
                'description' => $this->post->description,
                'user' => [
                    'name' => $this->post->user ? $this->post->user->name : null,
                    'email' => $this->post->user ? $this->post->user->email : null,
                ],
            ]
        ]);
    }
}

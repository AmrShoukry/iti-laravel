<x-layout>
    <x-slot:title>
        Show Post
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white rounded border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                <h2 class="text-base font-medium text-gray-700">Post Info</h2>
            </div>
            <div class="px-4 py-4">
                <div class="mb-2">
                    <h3 class="text-lg font-medium text-gray-800">Title :- <span class="font-normal">{{ $post['title'] }}</span></h3>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Description :-</h3>
                    <p class="text-gray-600">{{ $post['description'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                <h2 class="text-base font-medium text-gray-700">Post Creator Info</h2>
            </div>
            <div class="px-4 py-4">
                <div class="mb-2">
                    <h3 class="text-lg font-medium text-gray-800">Name :- <span class="font-normal">{{ $post['user']['name'] }}</span></h3>
                </div>
                <div class="mb-2">
                    <h3 class="text-lg font-medium text-gray-800">Email :- <span class="font-normal">{{ $post['user']['email'] }}</span></h3>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Created At :- <span class="font-normal">{{ $post->user->created_at->format('l jS \o\f F Y h:i:s A') }}</span></h3>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white rounded border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-base font-medium text-gray-700">Comments ({{ $post->comments->count() }})</h2>
            </div>
            <div class="px-4 py-4 space-y-4">
                @forelse($post->comments as $comment)
                    <div class="border border-gray-200 rounded p-3 space-y-2">
                        <div class="text-sm text-gray-700">
                            {{ $comment->body }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Posted at {{ $comment->created_at->format('Y-m-d H:i') }}
                        </div>

                        <div class="mt-2 flex items-center gap-2">
                            <!-- Edit comment -->
                            <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="flex-1 flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input
                                    type="text"
                                    name="body"
                                    value="{{ $comment->body }}"
                                    class="flex-1 rounded border border-gray-300 px-2 py-1 text-sm"
                                >
                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                    Update
                                </button>
                            </form>

                            <!-- Delete comment -->
                            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                                    onclick="return confirm('Delete this comment?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No comments yet.</p>
                @endforelse

                <!-- Add new comment -->
                <div class="mt-4 border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Add a Comment</h3>
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <textarea
                            name="body"
                            rows="3"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                            placeholder="Write your comment here..."
                        ></textarea>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700">
                            Submit Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-gray-600 text-white font-medium rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Back to All Posts
            </a>
        </div>
    </div>
</x-layout>

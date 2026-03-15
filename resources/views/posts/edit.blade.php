<x-layout>
    <x-slot:title>
        Edit Post
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Edit Post #{{ $post['id'] }}</h2>
            </div>

            <div class="px-6 py-4">
                <form method="POST" action="{{ route('posts.update', $post['id']) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $post['title']) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                        >
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea
                            id="description"
                            rows="5"
                            name="description"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                        >{{ old('description', $post['description']) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="creator" class="block text-sm font-medium text-gray-700 mb-1">Post Creator</label>
                        <select
                            id="creator"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border bg-white"
                            name="user_id"
                        >
                            @php
                                $selectedUserId = old('user_id', $post['user']['id']);
                            @endphp
                            @foreach($users as $user)
                                <option
                                    value="{{ $user['id'] }}"
                                    {{ (int) $selectedUserId === $user['id'] ? 'selected' : '' }}
                                >
                                    {{ $user['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <input type="file" name="image" accept=".jpg,.png" class="mt-2">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($post->image)
                    <p>Old Post Image</p>
                    <input type="hidden" name="oldImage" value="{{ $post->image }}" />
                    <div>
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" style="max-width:200px;">
                    </div>
                @endif

                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                    <input
                        type="text"
                        id="tags"
                        name="tags"
                        value="{{ old('tags', $post->tags->pluck('name')->implode(',')) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                    >
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                    <div class="flex justify-end">
                        <x-button type="secondary">
                            Update
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>

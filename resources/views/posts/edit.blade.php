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
                <form method="POST" action="{{ route('posts.update', $post['id']) }}">
                    @method('PUT')
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value={{ $post['title'] }}
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea
                            id="description"
                            rows="5"
                            name="description"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                        >{{ $post['description'] }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="creator" class="block text-sm font-medium text-gray-700 mb-1">Post Creator</label>
                        <select
                            id="creator"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border bg-white"
                            selected="2"
                            >
                            @foreach($users as $user)
                            <option
                                value="{{ $user['id'] }}"
                                {{ $post['user']['id'] == $user['id'] ? 'selected' : '' }}
                            >
                                {{ $user['name'] }}
                            </option>
                            @endforeach

                        </select>
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

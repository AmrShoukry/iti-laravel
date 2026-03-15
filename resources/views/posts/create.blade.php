<x-layout>
    <x-slot:title>
        Create Post
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Create New Post</h2>
            </div>

            <div class="px-6 py-4">
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
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
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="creator" class="block text-sm font-medium text-gray-700 mb-1">Post Creator</label>
                        <select
                            id="creator"
                            name="user_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border bg-white"
                        >
                            @foreach($users as $user)
                                <option
                                    value="{{ $user->id }}"
                                    {{ (int) old('user_id') === $user->id ? 'selected' : '' }}
                                >
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <input type="file" name="image" accept=".jpg,.png" class="mt-2">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <input
                            type="text"
                            id="tags"
                            name="tags"
                            value="{{ old('tags') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 py-2 px-3 border"
                        >
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="flex justify-end">
                        <button type="button" id="enhanceBtn">Enhance Post</button>
                        <x-button type="primary">
                            Create
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const enhanceBtn = document.getElementById('enhanceBtn');

    if (!enhanceBtn) {
        console.error('Enhance button not found!');
        return;
    }

    enhanceBtn.addEventListener('click', async () => {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const tagsInput = document.getElementById('tags').value;

        const tags = tagsInput
            .split(',')
            .map(t => t.trim())
            .filter(Boolean);

        enhanceBtn.disabled = true;
        enhanceBtn.textContent = 'Enhancing...';

        try {
            const response = await fetch('{{ route("posts.enhance") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title, description, tags })
            });

            const data = await response.json();

            if (!response.ok) {
                console.error('Server Error:', data);
                alert('Error: ' + (data.message || 'Check console for details.'));
            } else {
                console.log('Success:', data);
                if (data.title) {
                    document.getElementById('title').value = data.title;
                }
                if (data.description) {
                    document.getElementById('description').value = data.description;
                }
                if (data.tags && Array.isArray(data.tags)) {
                    document.getElementById('tags').value = data.tags.join(', ');
                }
            }
        } catch (err) {
            console.error('Fetch failed:', err);
            alert('Request failed: ' + err.message);
        } finally {
            enhanceBtn.disabled = false;
            enhanceBtn.textContent = 'Enhance Post';
        }
    });
});
    </script>
</x-layout>


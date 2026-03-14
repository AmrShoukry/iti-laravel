<x-layout>
    <x-slot:title>
        Posts
    </x-slot>

       <div class="text-center">
           <a href="{{ route('posts.create') }}" class="mt-4 px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
               Create Post
           </a>
       </div>


       <!-- Table Component -->
       <div class="mt-6 rounded-lg border border-gray-200">
           <div class="overflow-x-auto rounded-t-lg">
               <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                   <thead class="text-left">
                       <tr>
                           <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">#</th>
                           <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Title</th>
                           <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Posted By</th>
                           <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Created At</th>
                           <th class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">Actions</th>
                       </tr>
                   </thead>
                   <tbody class="divide-y divide-gray-200">
                    @foreach($posts as $post)
                       <tr>
                           <td class="px-4 py-2 font-medium whitespace-nowrap text-gray-900">{{$post['id']}}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{$post['title']}}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{$post?->user?->name}}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-gray-700">{{ $post->created_at->toDateString()  }}</td>
                           <td class="px-4 py-2 whitespace-nowrap text-gray-700 space-x-2">
                            <div class="flex gap-1">

                                <x-button type="primary"><a href="{{ route('posts.show', $post['id']) }}">View</a></x-button>
                                <x-button type="secondary"><a href="{{ route('posts.edit', $post['id']) }}" >Edit</a></x-button>
                                <form onsubmit="return confirm('Are you sure you want to delete this post?')" action="{{ route('posts.destroy', $post['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="danger">Delete</x-button>
                                </form>
                                <form
                                    onsubmit="return confirm('{{ $post->trashed() ? 'Are you sure you want to restore this post?' : 'Are you sure you want to soft delete this post?' }}')"
                                    action="{{ route('posts.toggle-soft-delete', $post->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <x-button type="secondary">
                                        {{ $post->trashed() ? 'Restore' : 'Soft Delete' }}
                                    </x-button>
                                </form>
                            </div>
                           </td>
                       </tr>
                       @endforeach
                   </tbody>
               </table>
           </div>


           {{ $posts->links() }}

           <a href="/posts/inertia">Inertia</a>

       <div id="appModal">
           <post-details-modal :id="1" />
        </div>
</x-layout>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold">Posts</h1>

      <!-- Create Post -->
      <AlertDialog v-model:open="createOpen">
        <AlertDialogTrigger as-child>
          <Button>
            Create Post
          </Button>
        </AlertDialogTrigger>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Create Post</AlertDialogTitle>
            <AlertDialogDescription>
              Fill in the form below to create a new post.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <form class="space-y-3" @submit.prevent="submitCreate">
            <div class="space-y-1">
              <label class="text-sm font-medium">Title</label>
              <input
                v-model="createForm.title"
                type="text"
                class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
              />
            </div>
            <div class="space-y-1">
              <label class="text-sm font-medium">Description</label>
              <textarea
                v-model="createForm.description"
                rows="3"
                class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
              ></textarea>
            </div>
            <div class="space-y-1">
              <label class="text-sm font-medium">Post Creator</label>
              <select
                v-model="createForm.user_id"
                class="w-full rounded border border-gray-300 px-3 py-2 text-sm bg-white"
              >
                <option value="">Select user</option>
                <option
                  v-for="user in users"
                  :key="user.id"
                  :value="user.id"
                >
                  {{ user.name }}
                </option>
              </select>
            </div>
            <AlertDialogFooter class="mt-4">
              <AlertDialogCancel type="button">Cancel</AlertDialogCancel>
              <AlertDialogAction type="submit">
                Create
              </AlertDialogAction>
            </AlertDialogFooter>
          </form>
        </AlertDialogContent>
      </AlertDialog>
    </div>

    <div v-if="paginatedPosts.length === 0" class="text-gray-500">
      No posts found.
    </div>

    <div v-else class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
      <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left font-medium text-gray-900">#</th>
            <th class="px-4 py-2 text-left font-medium text-gray-900">Title</th>
            <th class="px-4 py-2 text-left font-medium text-gray-900">Posted By</th>
            <th class="px-4 py-2 text-left font-medium text-gray-900">Created At</th>
            <th class="px-4 py-2 text-left font-medium text-gray-900">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="post in paginatedPosts" :key="post.id">
            <td class="px-4 py-2 font-medium text-gray-900">
              {{ post.id }}
            </td>
            <td class="px-4 py-2 text-gray-700">
              {{ post.title }}
            </td>
            <td class="px-4 py-2 text-gray-700">
              {{ post.user?.name ?? '—' }}
            </td>
            <td class="px-4 py-2 text-gray-700">
              {{ formatDate(post.created_at) }}
            </td>
            <td class="px-4 py-2">
              <div class="flex flex-wrap gap-2">
                <!-- View -->
                <AlertDialog>
                  <AlertDialogTrigger as-child>
                    <Button
                      variant="outline"
                      size="sm"
                    >
                      View
                    </Button>
                  </AlertDialogTrigger>
                  <AlertDialogContent class="max-w-xl">
                    <AlertDialogHeader>
                      <AlertDialogTitle>Post Details</AlertDialogTitle>
                    </AlertDialogHeader>

                    <div class="space-y-4">
                      <!-- Post info -->
                      <div class="border border-gray-200 rounded p-3 space-y-1">
                        <div class="font-semibold text-gray-800">
                          Title: <span class="font-normal">{{ post.title }}</span>
                        </div>
                        <div class="text-gray-700">
                          Description:
                          <p class="mt-1 text-sm">{{ post.description }}</p>
                        </div>
                      </div>

                      <!-- Creator info -->
                      <div class="border border-gray-200 rounded p-3 space-y-1" v-if="post.user">
                        <div class="font-semibold text-gray-800">
                          Name: <span class="font-normal">{{ post.user.name }}</span>
                        </div>
                        <div class="text-sm text-gray-700">
                          Email: <span class="font-normal">{{ post.user.email }}</span>
                        </div>
                        <div class="text-xs text-gray-500">
                          Created at:
                          <span class="font-normal">
                            {{ formatDateTime(post.user.created_at) }}
                          </span>
                        </div>
                      </div>

                      <!-- Comments -->
                      <div class="border border-gray-200 rounded p-3 space-y-2">
                        <div class="font-semibold text-gray-800">
                          Comments ({{ post.comments?.length ?? 0 }})
                        </div>
                        <div v-if="post.comments?.length" class="space-y-2 max-h-64 overflow-y-auto">
                          <div
                            v-for="comment in post.comments"
                            :key="comment.id"
                            class="border border-gray-100 rounded p-2 space-y-1"
                          >
                            <!-- Comment body / edit form -->
                            <div v-if="editingCommentId === comment.id" class="space-y-2">
                              <input
                                v-model="editCommentForm.body"
                                type="text"
                                class="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                              />
                              <div class="flex gap-2">
                                <Button
                                  size="xs"
                                  @click="submitEditComment(comment)"
                                >
                                  Save
                                </Button>
                                <Button
                                  size="xs"
                                  variant="outline"
                                  @click="cancelEditComment"
                                >
                                  Cancel
                                </Button>
                              </div>
                            </div>
                            <div v-else class="space-y-1">
                              <div class="text-sm text-gray-700">
                                {{ comment.body }}
                              </div>
                              <div class="text-xs text-gray-500 flex justify-between">
                                <span>
                                  {{ formatDateTime(comment.created_at) }}
                                </span>
                                <span v-if="comment.user">
                                  by {{ comment.user.name }}
                                </span>
                              </div>
                              <div class="flex gap-2 mt-1">
                                <Button
                                  size="xs"
                                  variant="outline"
                                  @click="startEditComment(comment)"
                                >
                                  Edit
                                </Button>
                                <Button
                                  size="xs"
                                  variant="destructive"
                                  @click="deleteComment(comment)"
                                >
                                  Delete
                                </Button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <p v-else class="text-sm text-gray-500">
                          No comments yet.
                        </p>

                        <!-- Add comment -->
                        <form class="mt-3 space-y-2" @submit.prevent="submitAddComment(post)">
                          <textarea
                            v-model="commentForm.body"
                            rows="2"
                            class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                            placeholder="Write a comment..."
                          ></textarea>
                          <Button
                            size="sm"
                            type="submit"
                          >
                            Add Comment
                          </Button>
                        </form>
                      </div>
                    </div>

                    <AlertDialogFooter class="mt-4">
                      <AlertDialogCancel>Close</AlertDialogCancel>
                    </AlertDialogFooter>
                  </AlertDialogContent>
                </AlertDialog>

                <!-- Edit -->
                <AlertDialog>
                  <AlertDialogTrigger as-child>
                    <Button
                      variant="outline"
                      size="sm"
                      @click="openEdit(post)"
                    >
                      Edit
                    </Button>
                  </AlertDialogTrigger>
                  <AlertDialogContent>
                    <AlertDialogHeader>
                      <AlertDialogTitle>Edit Post</AlertDialogTitle>
                    </AlertDialogHeader>
                    <form class="space-y-3" @submit.prevent="submitEdit(post)">
                      <div class="space-y-1">
                        <label class="text-sm font-medium">Title</label>
                        <input
                          v-model="editForm.title"
                          type="text"
                          class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                        />
                      </div>
                      <div class="space-y-1">
                        <label class="text-sm font-medium">Description</label>
                        <textarea
                          v-model="editForm.description"
                          rows="3"
                          class="w-full rounded border border-gray-300 px-3 py-2 text-sm"
                        ></textarea>
                      </div>
                      <div class="space-y-1">
                        <label class="text-sm font-medium">Post Creator</label>
                        <select
                          v-model="editForm.user_id"
                          class="w-full rounded border border-gray-300 px-3 py-2 text-sm bg-white"
                        >
                          <option value="">Select user</option>
                          <option
                            v-for="user in users"
                            :key="user.id"
                            :value="user.id"
                          >
                            {{ user.name }}
                          </option>
                        </select>
                      </div>
                      <AlertDialogFooter class="mt-4">
                        <AlertDialogCancel type="button">Cancel</AlertDialogCancel>
                        <AlertDialogAction type="submit">
                          Save
                        </AlertDialogAction>
                      </AlertDialogFooter>
                    </form>
                  </AlertDialogContent>
                </AlertDialog>

                <!-- Delete -->
                <AlertDialog>
                  <AlertDialogTrigger as-child>
                    <Button
                      variant="destructive"
                      size="sm"
                    >
                      Delete
                    </Button>
                  </AlertDialogTrigger>
                  <AlertDialogContent>
                    <AlertDialogHeader>
                      <AlertDialogTitle>Delete this post?</AlertDialogTitle>
                      <AlertDialogDescription>
                        This action cannot be undone.
                      </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                      <AlertDialogCancel>Cancel</AlertDialogCancel>
                      <AlertDialogAction @click="submitDelete(post)">
                        Delete
                      </AlertDialogAction>
                    </AlertDialogFooter>
                  </AlertDialogContent>
                </AlertDialog>

                <!-- Soft delete / restore -->
                <AlertDialog>
                  <AlertDialogTrigger as-child>
                    <Button
                      variant="outline"
                      size="sm"
                    >
                      {{ post.deleted_at ? 'Restore' : 'Soft Delete' }}
                    </Button>
                  </AlertDialogTrigger>
                  <AlertDialogContent>
                    <AlertDialogHeader>
                      <AlertDialogTitle>
                        {{ post.deleted_at ? 'Restore this post?' : 'Soft delete this post?' }}
                      </AlertDialogTitle>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                      <AlertDialogCancel>Cancel</AlertDialogCancel>
                      <AlertDialogAction @click="submitToggleSoftDelete(post)">
                        Yes
                      </AlertDialogAction>
                    </AlertDialogFooter>
                  </AlertDialogContent>
                </AlertDialog>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="border-t border-gray-200 px-4 py-2 flex justify-end gap-2 text-xs">
        <button
          v-for="link in paginationLinks"
          :key="link.label + (link.url || '')"
          type="button"
          class="px-3 py-1 rounded border text-sm"
          :class="[
            link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-200',
            !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50',
          ]"
          :disabled="!link.url || link.active"
          v-html="link.label"
          @click="goToPage(link)"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button'
import { router, useForm, usePoll } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  posts: {
    type: Object,
    required: true,
    default: () => ({ data: [], links: [] }),
  },
  users: {
    type: Array,
    required: true,
    default: () => [],
  },
})

// Polling to keep posts fresh without manual reload
usePoll(5000, {
  onStart() {
    router.reload({ only: ['posts'] })
  },
})

const paginatedPosts = computed(() => props.posts?.data ?? [])
const paginationLinks = computed(() => props.posts?.links ?? [])

const createOpen = ref(false)
const createForm = useForm({
  title: '',
  description: '',
  user_id: '',
})

const editForm = useForm({
  title: '',
  description: '',
  user_id: '',
})

const commentForm = useForm({
  body: '',
})

const editCommentForm = useForm({
  body: '',
})

const editingCommentId = ref(null)

const formatDate = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleDateString()
}

const formatDateTime = (value) => {
  if (!value) return '—'
  return new Date(value).toLocaleString()
}

const openEdit = (post) => {
  editForm.title = post.title
  editForm.description = post.description
  editForm.user_id = post.user?.id ?? ''
}

const submitCreate = () => {
  createForm.post('/posts/store', {
    onSuccess: () => {
      createOpen.value = false
      createForm.reset()
    },
    preserveScroll: true,
  })
}

const submitEdit = (post) => {
  editForm.put(`/posts/${post.id}`, {
    preserveScroll: true,
  })
}

const submitDelete = (post) => {
  router.delete(`/posts/${post.id}`, {
    preserveScroll: true,
  })
}

const submitToggleSoftDelete = (post) => {
  router.patch(`/posts/${post.id}/toggle-soft-delete`, {
    preserveScroll: true,
  })
}

const submitAddComment = (post) => {
  commentForm.post(`/posts/${post.id}/comments`, {
    onSuccess: () => {
      commentForm.reset('body')
    },
    preserveScroll: true,
  })
}

const startEditComment = (comment) => {
  editingCommentId.value = comment.id
  editCommentForm.body = comment.body
}

const cancelEditComment = () => {
  editingCommentId.value = null
  editCommentForm.reset('body')
}

const submitEditComment = (comment) => {
  editCommentForm.put(`/comments/${comment.id}`, {
    onSuccess: () => {
      editingCommentId.value = null
      editCommentForm.reset('body')
    },
    preserveScroll: true,
  })
}

const deleteComment = (comment) => {
  router.delete(`/comments/${comment.id}`, {
    preserveScroll: true,
  })
}

const goToPage = (link) => {
  if (!link.url || link.active) {
    return
  }

  router.get(link.url, {}, {
    preserveScroll: true,
    preserveState: true,
  })
}
</script>

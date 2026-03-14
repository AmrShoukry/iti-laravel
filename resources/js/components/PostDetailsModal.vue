<template>
    <div>
      <button @click="openModal" class="px-4 py-2 bg-blue-500 text-white rounded">
        View Post
      </button>

      <div
        v-if="show"
        class="fixed inset-0 z-50 grid place-content-center bg-black/50 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalTitle"
      >
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg relative">
          <div class="flex items-start justify-between">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-900 sm:text-2xl">
              <template v-if="loading">Loading...</template>
              <template v-else-if="error">{{ error }}</template>
              <template v-else>{{ post.title }}</template>
            </h2>
            <button
              @click="closeModal"
              type="button"
              class="-me-4 -mt-4 rounded-full p-2 text-gray-400 transition-colors hover:bg-gray-50 hover:text-gray-600 focus:outline-none"
              aria-label="Close"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="mt-4">
            <template v-if="loading">
              <p class="text-gray-700">Fetching post details...</p>
            </template>
            <template v-else-if="error">
              <p class="text-red-500">{{ error }}</p>
            </template>
            <template v-else>
              <p class="text-gray-700">{{ post.description }}</p>
              <p class="font-medium mt-2">Author: {{ post.user.name }}</p>
              <p class="text-gray-500">{{ post.user.email }}</p>
            </template>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'

  const props = defineProps({
    id: { type: [Number, String], required: true }
  })

  const show = ref(false)
  const loading = ref(false)
  const error = ref(null)
  const post = ref({
    title: '',
    description: '',
    user: { name: '', email: '' }
  })

  function openModal() {
    show.value = true
    loading.value = true
    error.value = null

    axios.get(`/posts/${props.id}/details`)
      .then(res => {
        post.value = res.data.post
      })
      .catch(err => {
        error.value = 'Failed to load post.'
        console.error(err)
      })
      .finally(() => {
        loading.value = false
      })
  }

  function closeModal() {
    show.value = false
  }
  </script>

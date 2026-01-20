<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 py-12 px-4 sm:px-6 transition-colors">
    <div class="max-w-5xl mx-auto">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
          <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">
            Mes articles
          </h1>
          <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            {{ posts.length }} article{{ posts.length > 1 ? 's' : '' }}
          </p>
        </div>
        <router-link 
          to="/create" 
          class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-md text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 transition"
        >
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Nouvel article
        </router-link>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="space-y-4">
        <div v-for="n in 3" :key="n" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 animate-pulse">
          <div class="flex gap-4">
            <div class="w-24 h-20 bg-zinc-200 dark:bg-zinc-800 rounded-md"></div>
            <div class="flex-1">
              <div class="h-4 bg-zinc-200 dark:bg-zinc-800 rounded w-3/4 mb-2"></div>
              <div class="h-3 bg-zinc-200 dark:bg-zinc-800 rounded w-1/2 mb-2"></div>
              <div class="h-3 bg-zinc-200 dark:bg-zinc-800 rounded w-1/4"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error -->
      <div v-else-if="errorMessage" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
        <p class="text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
      </div>

      <!-- Empty -->
      <div v-else-if="posts.length === 0" class="text-center py-16 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg">
        <svg class="mx-auto h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        <h3 class="mt-4 text-sm font-medium text-zinc-900 dark:text-zinc-50">Aucun article</h3>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Commencez par creer votre premier article.</p>
        <router-link 
          to="/create" 
          class="inline-block mt-6 px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-700 transition"
        >
          Creer un article
        </router-link>
      </div>

      <!-- Posts list -->
      <div v-else class="space-y-3">
        <article 
          v-for="post in posts" 
          :key="post.id" 
          class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden hover:border-zinc-300 dark:hover:border-zinc-700 transition"
        >
          <div class="flex">
            <!-- Image -->
            <div class="w-32 h-28 flex-shrink-0 bg-zinc-100 dark:bg-zinc-800">
              <img 
                v-if="post.image_url"
                :src="post.image_url" 
                :alt="post.title"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
            </div>

            <!-- Content -->
            <div class="flex-1 p-4 flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <span 
                    v-if="post.category" 
                    class="px-2 py-0.5 text-xs font-medium rounded"
                    :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
                  >
                    {{ post.category.name }}
                  </span>
                  <span class="text-xs text-zinc-400">{{ formatDate(post.created_at) }}</span>
                </div>
                <router-link :to="{ name: 'post-detail', params: { id: post.id } }">
                  <h3 class="text-base font-medium text-zinc-900 dark:text-zinc-50 hover:text-zinc-600 dark:hover:text-zinc-300 transition line-clamp-1">
                    {{ post.title }}
                  </h3>
                </router-link>
              </div>

              <!-- Actions -->
              <div class="flex items-center justify-between mt-2">
                <div class="flex items-center gap-3 text-xs text-zinc-400">
                  <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ post.likes_count || 0 }}
                  </span>
                  <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    {{ post.comments_count || 0 }}
                  </span>
                </div>

                <div class="flex items-center gap-1">
                  <router-link 
                    :to="{ name: 'post-edit', params: { id: post.id } }" 
                    class="p-1.5 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 rounded transition"
                    title="Modifier"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </router-link>
                  <button 
                    @click="confirmDelete(post.id)" 
                    class="p-1.5 text-zinc-400 hover:text-red-500 rounded transition"
                    title="Supprimer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getMyPosts, deletePost } from '@/services/api'

const posts = ref([])
const loading = ref(true)
const errorMessage = ref('')

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const fetchPosts = async () => {
  loading.value = true
  try {
    const response = await getMyPosts()
    if (response.data.data && response.data.data.data) {
      posts.value = response.data.data.data
    } else {
      posts.value = response.data.data || []
    }
  } catch (error) {
    errorMessage.value = "Impossible de recuperer vos articles."
  } finally {
    loading.value = false
  }
}

const confirmDelete = async (id) => {
  if (confirm("Supprimer cet article ?")) {
    try {
      await deletePost(id)
      posts.value = posts.value.filter(p => p.id !== id)
    } catch (error) {
      alert("Erreur lors de la suppression")
    }
  }
}

onMounted(() => {
  fetchPosts()
})
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

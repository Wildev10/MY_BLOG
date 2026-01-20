<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 py-12 px-4 sm:px-6 transition-colors">
    <div class="max-w-3xl mx-auto">
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Article -->
      <article v-else-if="post">
        <!-- Back button -->
        <button
          @click="$router.push('/')"
          class="mb-6 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition flex items-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Retour
        </button>

        <!-- Header -->
        <header class="mb-8">
          <!-- Categorie -->
          <div v-if="post.category" class="mb-3">
            <span
              class="inline-block px-2 py-0.5 text-xs font-medium rounded"
              :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
            >
              {{ post.category.name }}
            </span>
          </div>

          <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight leading-tight mb-4">
            {{ post.title }}
          </h1>

          <!-- Author info -->
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
              <span class="text-zinc-600 dark:text-zinc-400 font-medium text-sm">
                {{ post.user?.name?.charAt(0) || '?' }}
              </span>
            </div>
            <div>
              <p class="text-sm font-medium text-zinc-900 dark:text-zinc-50">{{ post.user?.name || 'Anonyme' }}</p>
              <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ formatDate(post.created_at) }}</p>
            </div>
          </div>
        </header>

        <!-- Image -->
        <div v-if="post.image_url" class="mb-8 rounded-lg overflow-hidden">
          <img 
            :src="post.image_url" 
            :alt="post.title"
            class="w-full max-h-96 object-cover"
          />
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6 sm:p-8">
          <div 
            class="prose prose-zinc dark:prose-invert max-w-none"
            v-html="post.content"
          ></div>
        </div>

        <!-- Actions bar -->
        <div class="mt-6 flex items-center justify-between">
          <!-- Like button -->
          <div class="flex items-center gap-4">
            <button
              v-if="authStore.isAuthenticated"
              @click="handleLike"
              class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition"
              :class="post.is_liked 
                ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' 
                : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700'"
            >
              <svg class="w-4 h-4" :fill="post.is_liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              {{ post.likes_count || 0 }}
            </button>
            
            <span v-else class="flex items-center gap-1.5 text-sm text-zinc-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
              {{ post.likes_count || 0 }}
            </span>
          </div>

          <!-- Share -->
          <ShareButtons 
            :title="post.title"
            :url="shareUrl"
          />
        </div>

        <!-- Author actions -->
        <div v-if="isAuthor" class="mt-6 flex gap-3">
          <button
            @click="editPost"
            class="flex-1 px-4 py-2.5 text-sm font-medium rounded-md text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 transition"
          >
            Modifier
          </button>
          <button
            @click="confirmDelete"
            class="px-4 py-2.5 text-sm font-medium rounded-md text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 transition"
          >
            Supprimer
          </button>
        </div>

        <!-- Comments -->
        <CommentSection v-if="post" :key="`comments-${post.id}-${commentKey}`" :post-id="post.id" class="mt-10" />
      </article>

      <!-- Error -->
      <div v-else class="text-center py-20">
        <p class="text-zinc-500 dark:text-zinc-400">Article introuvable</p>
        <button
          @click="$router.push('/')"
          class="mt-4 text-sm font-medium text-zinc-900 dark:text-zinc-50 hover:underline"
        >
          Retour a l'accueil
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getPost, deletePost, toggleLike } from '@/services/api'
import CommentSection from '@/components/CommentSection.vue' 
import ShareButtons from '@/components/ShareButtons.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const post = ref(null)
const loading = ref(true)
const commentKey = ref(0)

const isAuthor = computed(() => {
  return authStore.user && post.value && authStore.user.id === post.value.user_id
})

const fetchPost = async () => {
  loading.value = true
  try {
    const response = await getPost(route.params.id)
    post.value = response.data.data
    commentKey.value++
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

watch(() => route.params.id, (newId, oldId) => {
  if (newId && newId !== oldId) {
    fetchPost()
  }
})

watch(() => route.fullPath, () => {
  if (route.name === 'post-detail') {
    fetchPost()
  }
}, { immediate: false })

const editPost = () => {
  router.push(`/posts/${post.value.id}/edit`)
}

const confirmDelete = () => {
  if (confirm('Supprimer cet article ?')) {
    handleDelete()
  }
}

const handleDelete = async () => {
  try {
    await deletePost(post.value.id)
    router.push('/')
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

onMounted(() => {
  fetchPost()
})

const handleLike = async () => {
  try {
    const response = await toggleLike(post.value.id)
    post.value.is_liked = response.data.liked
    post.value.likes_count = response.data.likes_count
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const shareUrl = computed(() => {
  if (typeof window !== 'undefined') {
    return window.location.origin + '/posts/' + route.params.id
  }
  return ''
})
</script>

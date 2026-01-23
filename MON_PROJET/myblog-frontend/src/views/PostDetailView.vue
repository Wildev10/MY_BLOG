<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-gradient-to-br from-blue-100/20 to-purple-100/20 dark:from-blue-900/5 dark:to-purple-900/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-1/4 left-0 w-[500px] h-[500px] bg-gradient-to-tr from-emerald-100/20 to-cyan-100/20 dark:from-emerald-900/5 dark:to-cyan-900/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-3xl mx-auto py-12 px-4 sm:px-6">
      <!-- Loading -->
      <div v-if="loading" class="flex flex-col items-center justify-center py-20 space-y-4 animate-pulse">
        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
          <svg class="animate-spin w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p class="text-slate-500 dark:text-slate-400">Chargement de l'article...</p>
      </div>

      <!-- Article -->
      <article v-else-if="post" class="animate-fadeIn">
        <!-- Back button -->
        <button
          @click="$router.push('/')"
          class="group mb-8 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-sm"
        >
          <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Retour aux articles
        </button>

        <!-- Header -->
        <header class="mb-10">
          <!-- Category -->
          <div v-if="post.category" class="mb-6">
            <span
              class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold tracking-wide uppercase rounded-full shadow-sm"
              :style="{ backgroundColor: post.category.color + '15', color: post.category.color, border: '1px solid ' + post.category.color + '30' }"
            >
              <span class="w-2 h-2 rounded-full" :style="{ backgroundColor: post.category.color }"></span>
              {{ post.category.name }}
            </span>
          </div>

          <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-8">
            {{ post.title }}
          </h1>

          <!-- Author card -->
          <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 p-5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
            <div class="flex items-center gap-4 flex-1">
              <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center overflow-hidden ring-2 ring-white dark:ring-slate-900 shadow-lg">
                <img 
                  v-if="post.user?.avatar_url" 
                  :src="post.user.avatar_url" 
                  class="w-full h-full object-cover"
                  alt=""
                />
                <span v-else class="text-slate-500 dark:text-slate-400 font-bold text-xl">
                  {{ post.user?.name?.charAt(0) || '?' }}
                </span>
              </div>
              <div>
                <p class="text-base font-bold text-slate-900 dark:text-white">{{ post.user?.name || 'Auteur inconnu' }}</p>
                <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                  <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDate(post.created_at) }}
                  </span>
                  <span class="text-slate-300 dark:text-slate-600">•</span>
                  <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ Math.ceil(post.content?.length / 1000) || 1 }} min de lecture
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Follow button placeholder -->
            <button class="px-5 py-2.5 text-sm font-semibold bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors shadow-lg shadow-slate-900/20 dark:shadow-none">
              Suivre
            </button>
          </div>
        </header>

        <!-- Image -->
        <div v-if="post.image_url" class="mb-12 -mx-4 sm:mx-0 group">
          <div class="relative overflow-hidden sm:rounded-2xl shadow-2xl shadow-slate-200/50 dark:shadow-none">
            <img 
              :src="post.image_url" 
              :alt="post.title"
              class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-[1.02]"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
          </div>
        </div>

        <!-- Content -->
        <div class="prose prose-lg prose-slate dark:prose-invert max-w-none mb-16 prose-headings:font-extrabold prose-headings:tracking-tight prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-blockquote:border-l-slate-900 dark:prose-blockquote:border-l-white prose-blockquote:bg-slate-50 dark:prose-blockquote:bg-slate-900 prose-blockquote:px-6 prose-blockquote:py-4 prose-blockquote:rounded-r-xl prose-code:bg-slate-100 dark:prose-code:bg-slate-900 prose-code:px-2 prose-code:py-1 prose-code:rounded-lg prose-code:before:content-none prose-code:after:content-none first-letter:text-6xl first-letter:font-extrabold first-letter:text-slate-900 dark:first-letter:text-white first-letter:mr-3 first-letter:float-left first-letter:leading-none">
          <div v-html="post.content"></div>
        </div>
        
        <!-- Actions bar -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-6 my-12 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
          <div class="flex items-center gap-4 sm:gap-6">
            <!-- Like button -->
            <button
              @click="handleLike"
              class="flex items-center gap-3 group transition-all"
              :class="post.is_liked ? 'text-red-500' : 'text-slate-500 hover:text-red-500'"
            >
              <div 
                class="p-3 rounded-xl transition-all"
                :class="post.is_liked 
                  ? 'bg-red-50 dark:bg-red-900/20' 
                  : 'bg-slate-100 dark:bg-slate-800 group-hover:bg-red-50 dark:group-hover:bg-red-900/20'"
              >
                <svg class="w-6 h-6 transition-transform group-hover:scale-110" :fill="post.is_liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <div class="text-left">
                <span class="font-bold text-lg block">{{ post.likes_count || 0 }}</span>
                <span class="text-xs text-slate-400">J'aime</span>
              </div>
            </button>
            
            <div class="h-10 w-px bg-slate-200 dark:bg-slate-800"></div>

            <ShareButtons 
              :title="post.title"
              :url="shareUrl"
            />
          </div>

          <!-- Author actions -->
          <div v-if="isAuthor" class="flex gap-2 w-full sm:w-auto">
            <button 
              @click="editPost" 
              class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors flex items-center justify-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              Modifier
            </button>
            <button 
              @click="confirmDelete" 
              class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-xl transition-colors flex items-center justify-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              Supprimer
            </button>
          </div>
        </div>

        <!-- Comments section -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 mt-12 shadow-sm">
          <div class="flex items-center gap-3 mb-8">
            <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded-xl">
              <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-slate-900 dark:text-white">Discussion</h3>
              <p class="text-sm text-slate-500 dark:text-slate-400">Partagez votre avis sur cet article</p>
            </div>
          </div>
          <CommentSection v-if="post" :key="`comments-${post.id}-${commentKey}`" :post-id="post.id" />
        </div>
      </article>

      <!-- Error state -->
      <div v-else class="text-center py-20">
        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center">
          <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Article introuvable</h3>
        <p class="text-slate-500 dark:text-slate-400 mb-6">L'article que vous cherchez n'existe pas ou a été supprimé.</p>
        <button
          @click="$router.push('/')"
          class="px-6 py-3 text-sm font-semibold bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors shadow-lg shadow-slate-900/20 dark:shadow-none"
        >
          Retour à l'accueil
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
  if (confirm('Voulez-vous vraiment supprimer cet article ? Cette action est irréversible.')) {
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
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return;
  }
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

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

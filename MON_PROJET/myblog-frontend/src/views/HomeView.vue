<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
      <!-- Header sobre -->
      <div class="mb-8">
        <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
          Articles
        </h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
          Decouvrez les dernieres publications de la communaute
        </p>
      </div>

      <!-- Barre de recherche -->
      <div class="mb-6">
        <div class="relative max-w-md">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Rechercher..."
            class="w-full px-4 py-2 pl-10 text-sm border border-zinc-200 dark:border-zinc-800 rounded-lg bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
          />
          <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <button
            v-if="searchQuery"
            @click="clearSearch"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <p v-if="searchQuery" class="mt-2 text-xs text-zinc-500">
          {{ posts.length }} resultat(s) pour "{{ searchQuery }}"
        </p>
      </div>

      <!-- Filtres categories -->
      <div class="mb-8 flex flex-wrap gap-2">
        <button
          @click="filterByCategory(null)"
          :class="[
            'px-3 py-1.5 text-sm font-medium rounded-md transition-colors',
            selectedCategory === null && !searchQuery
              ? 'bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900'
              : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800'
          ]"
        >
          Tous
        </button>
        <button
          v-for="category in categories"
          :key="category.id"
          @click="filterByCategory(category)"
          :class="[
            'px-3 py-1.5 text-sm font-medium rounded-md transition-colors',
            selectedCategory?.id === category.id && !searchQuery
              ? 'text-white'
              : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800'
          ]"
          :style="selectedCategory?.id === category.id && !searchQuery ? { backgroundColor: category.color } : {}"
        >
          {{ category.name }}
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <svg class="animate-spin h-6 w-6 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Liste articles -->
      <div v-else-if="posts.length > 0" class="space-y-0 divide-y divide-zinc-100 dark:divide-zinc-800">
        <article
          v-for="post in posts"
          :key="post.id"
          class="py-6 first:pt-0 cursor-pointer group"
          @click="goToPost(post.id)"
        >
          <div class="flex gap-6">
            <div class="flex-1 min-w-0">
              <!-- Meta auteur -->
              <div class="flex items-center gap-2 mb-2">
                <div class="w-5 h-5 rounded-full overflow-hidden bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
                  <img 
                    v-if="post.user?.avatar_url" 
                    :src="post.user.avatar_url" 
                    :alt="post.user?.name"
                    class="w-full h-full object-cover"
                  />
                  <span v-else class="text-[10px] font-medium text-zinc-500">
                    {{ post.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>
                <span class="text-[13px] text-zinc-600 dark:text-zinc-400">{{ post.user?.name || 'Anonyme' }}</span>
                <span class="text-zinc-300 dark:text-zinc-700">·</span>
                <span class="text-[13px] text-zinc-500">{{ formatDate(post.created_at) }}</span>
              </div>
              
              <!-- Titre -->
              <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-50 leading-snug mb-1.5 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                {{ post.title }}
              </h2>
              
              <!-- Extrait -->
              <p class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 mb-3">
                {{ stripHtml(post.content) }}
              </p>
              
              <!-- Footer -->
              <div class="flex items-center gap-4">
                <span 
                  v-if="post.category"
                  class="text-xs px-2 py-0.5 rounded text-zinc-600 dark:text-zinc-400"
                  :style="{ backgroundColor: post.category.color + '15' }"
                >
                  {{ post.category.name }}
                </span>
                <div class="flex items-center gap-3 text-xs text-zinc-400">
                  <button
                    v-if="authStore.isAuthenticated"
                    @click.stop="handleLike(post)"
                    class="flex items-center gap-1 transition-colors"
                    :class="post.is_liked ? 'text-red-500' : 'hover:text-red-500'"
                  >
                    <svg class="w-4 h-4" :fill="post.is_liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ post.likes_count || 0 }}
                  </button>
                  <span v-else class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ post.likes_count || 0 }}
                  </span>
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    {{ post.comments_count || 0 }}
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Thumbnail -->
            <div v-if="post.image_url" class="hidden sm:block flex-shrink-0">
              <img :src="post.image_url" :alt="post.title" class="w-32 h-24 object-cover rounded-lg" />
            </div>
          </div>
        </article>
      </div>

      <!-- Aucun article -->
      <div v-else class="text-center py-20">
        <svg class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <p class="text-zinc-500 dark:text-zinc-400 text-sm mb-4">
          {{ searchQuery 
            ? `Aucun resultat pour "${searchQuery}"` 
            : selectedCategory 
              ? `Aucun article dans "${selectedCategory.name}"` 
              : 'Aucun article pour le moment' 
          }}
        </p>
        <button
          v-if="searchQuery || selectedCategory"
          @click="clearAll"
          class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
        >
          Voir tous les articles
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getPosts, getCategories, getCategoryPosts, searchPosts, toggleLike } from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()

const posts = ref([])
const categories = ref([])
const selectedCategory = ref(null)
const searchQuery = ref('')
const loading = ref(true)
let searchTimeout = null

const stripHtml = (html) => {
  if (!html) return ''
  return html.replace(/<[^>]*>/g, '').substring(0, 200)
}

const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const fetchPosts = async () => {
  loading.value = true
  try {
    const response = await getPosts()
    posts.value = response.data.data.data || response.data.data
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const filterByCategory = async (category) => {
  searchQuery.value = ''
  selectedCategory.value = category
  loading.value = true
  try {
    if (category === null) {
      await fetchPosts()
    } else {
      const response = await getCategoryPosts(category.slug)
      posts.value = response.data.data.data || response.data.data
    }
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  if (searchTimeout) clearTimeout(searchTimeout)
  if (searchQuery.value.trim() === '') {
    fetchPosts()
    selectedCategory.value = null
    return
  }
  searchTimeout = setTimeout(async () => {
    loading.value = true
    selectedCategory.value = null
    try {
      const response = await searchPosts(searchQuery.value)
      posts.value = response.data.data.data || response.data.data
    } catch (error) {
      console.error('Erreur:', error)
      posts.value = []
    } finally {
      loading.value = false
    }
  }, 500)
}

const handleLike = async (post) => {
  try {
    const response = await toggleLike(post.id)
    post.is_liked = response.data.liked
    post.likes_count = response.data.likes_count
  } catch (error) {
    console.error('Erreur:', error)
    if (error.response?.status === 401) router.push('/login')
  }
}

const clearSearch = () => {
  searchQuery.value = ''
  fetchPosts()
}

const clearAll = () => {
  searchQuery.value = ''
  selectedCategory.value = null
  fetchPosts()
}

const goToPost = (id) => router.push(`/posts/${id}`)

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date
  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  if (days === 0) return "Aujourd'hui"
  if (days === 1) return 'Hier'
  if (days < 7) return `Il y a ${days} jours`
  return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
}

onMounted(async () => {
  await fetchCategories()
  await fetchPosts()
})
</script>

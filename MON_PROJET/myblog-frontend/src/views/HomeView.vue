<template>
  <div class="min-h-screen bg-white dark:bg-slate-950">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">

      <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-12 pb-16 lg:pt-20 lg:pb-24">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 rounded-full text-sm font-medium text-slate-600 dark:text-slate-400 mb-6">
            Bienvenue sur MyBlog
          </div>
          
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-6 text-balance">
            Découvrez des histoires qui inspirent
          </h1>
          
          <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 leading-relaxed mb-8">
            Explorez les connaissances, idées et récits partagés par notre communauté de passionnés.
          </p>

          <!-- Search Bar Premium -->
          <div class="relative max-w-xl mx-auto">
            <div class="relative group">
              <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
              <div class="relative flex items-center">
                <input
                  v-model="searchQuery"
                  @input="handleSearch"
                  type="text"
                  placeholder="Rechercher un article, un sujet..."
                  class="w-full px-6 py-4 pl-14 text-base bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all shadow-lg shadow-slate-200/50 dark:shadow-none"
                />
                <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button
                  v-if="searchQuery"
                  @click="clearSearch"
                  class="absolute right-4 top-1/2 transform -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-all"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Categories Pills -->
        <div class="flex flex-wrap justify-center gap-2 mb-12">
          <button
            @click="filterByCategory(null)"
            class="px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 border-2"
            :class="[
              selectedCategory === null && !searchQuery
                ? 'bg-slate-900 border-slate-900 text-white dark:bg-white dark:border-white dark:text-slate-900 shadow-lg shadow-slate-900/20 dark:shadow-white/10'
                : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700 hover:text-slate-900 dark:hover:text-white hover:shadow-md'
            ]"
          >
            <span class="flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
              </svg>
              Tous
            </span>
          </button>
          <button
            v-for="category in categories"
            :key="category.id"
            @click="filterByCategory(category)"
            class="px-5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 border-2"
            :class="[
              selectedCategory?.id === category.id && !searchQuery
                ? 'text-white border-transparent shadow-lg'
                : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700 hover:text-slate-900 dark:hover:text-white hover:shadow-md'
            ]"
            :style="selectedCategory?.id === category.id && !searchQuery ? { backgroundColor: category.color, borderColor: category.color, boxShadow: `0 10px 25px -5px ${category.color}40` } : {}"
          >
            {{ category.name }}
          </button>
        </div>
      </div>
    </section>

    <!-- Articles Section -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pb-20">
      <!-- Section Header -->
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
          {{ selectedCategory ? selectedCategory.name : 'Derniers articles' }}
        </h2>
        <span class="text-sm text-slate-500 dark:text-slate-400">
          {{ posts.length }} article{{ posts.length > 1 ? 's' : '' }}
        </span>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="space-y-8">
        <div v-for="i in 3" :key="i" class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 animate-pulse">
          <div class="flex gap-6">
            <div class="flex-1 space-y-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-200 dark:bg-slate-800 rounded-full"></div>
                <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-32"></div>
              </div>
              <div class="h-6 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
              <div class="space-y-2">
                <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded"></div>
                <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-5/6"></div>
              </div>
            </div>
            <div class="hidden sm:block w-40 h-28 bg-slate-200 dark:bg-slate-800 rounded-xl"></div>
          </div>
        </div>
      </div>

      <!-- Articles Grid -->
      <div v-else-if="posts.length > 0" class="space-y-6">
        <article
          v-for="(post, index) in posts"
          :key="post.id"
          class="group relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-none overflow-hidden cursor-pointer"
          :class="{ 'animate-fade-in': true }"
          :style="{ animationDelay: `${index * 50}ms` }"
          @click="goToPost(post.id)"
        >
          <div class="p-6 flex flex-col sm:flex-row gap-6">
            <div class="flex-1 space-y-4">
              <!-- Author & Meta -->
              <div class="flex items-center gap-3">
                <router-link 
                  :to="`/users/${post.user?.id}`" 
                  @click.stop
                  class="flex items-center gap-3 hover:opacity-80 transition-opacity"
                >
                  <div class="relative">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center ring-2 ring-white dark:ring-slate-900">
                      <img 
                        v-if="post.user?.avatar_url" 
                        :src="post.user.avatar_url" 
                        class="w-full h-full object-cover"
                        alt="" 
                      />
                      <span v-else class="text-slate-600 dark:text-slate-300 font-bold text-sm">
                        {{ post.user?.name?.charAt(0)?.toUpperCase() }}
                      </span>
                    </div>
                  </div>
                  <div>
                    <p class="font-semibold text-slate-900 dark:text-white text-sm">{{ post.user?.name }}</p>
                    <p class="text-xs text-slate-500">{{ formatDate(post.created_at) }}</p>
                  </div>
                </router-link>
              </div>

              <!-- Title & Excerpt -->
              <div class="space-y-2">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                  {{ post.title }}
                </h3>
                <p class="text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2 text-sm">
                  {{ stripHtml(post.content) }}
                </p>
              </div>
              
              <!-- Footer -->
              <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-3 flex-wrap">
                  <span 
                    v-if="post.category"
                    class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold"
                    :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
                  >
                    {{ post.category.name }}
                  </span>
                  <span class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ Math.max(1, Math.ceil(post.content.length / 1000)) }} min
                  </span>
                </div>
                
                <div class="flex items-center gap-4">
                  <span v-if="post.likes_count > 0" class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    {{ post.likes_count }}
                  </span>
                  <span v-if="post.comments_count > 0" class="flex items-center gap-1.5 text-sm text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    {{ post.comments_count }}
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Thumbnail -->
            <div v-if="post.image_url" class="hidden sm:block flex-shrink-0 w-44 h-32">
              <div class="w-full h-full rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800">
                <img 
                  :src="post.image_url" 
                  :alt="post.title" 
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                />
              </div>
            </div>
          </div>
          
          <!-- Hover indicator -->
          <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
        </article>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 px-6">
        <div class="max-w-md mx-auto">
          <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-3xl flex items-center justify-center">
            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
            {{ searchQuery ? 'Aucun résultat' : 'Pas encore d\'articles' }}
          </h3>
          <p class="text-slate-500 dark:text-slate-400 mb-6">
            {{ searchQuery ? `Aucun article ne correspond à "${searchQuery}"` : "Soyez le premier à partager une histoire !" }}
          </p>
          <div class="flex flex-wrap justify-center gap-3">
            <button
              v-if="searchQuery || selectedCategory"
              @click="clearAll"
              class="px-6 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
            >
              Effacer les filtres
            </button>
            <router-link
              to="/create"
              class="px-6 py-3 text-sm font-semibold text-white bg-slate-900 dark:bg-white dark:text-slate-900 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors shadow-lg shadow-slate-900/20"
            >
              Écrire un article
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getPosts, getCategories, getCategoryPosts, searchPosts } from '@/services/api'

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
    } finally {
      loading.value = false
    }
  }, 400)
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

const goToPost = (id) => {
  router.push(`/posts/${id}`)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffTime = Math.abs(now - date)
  const diffMinutes = Math.floor(diffTime / (1000 * 60))
  const diffHours = Math.floor(diffTime / (1000 * 60 * 60))
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24))
  
  if (diffMinutes < 1) return "À l'instant"
  if (diffMinutes === 1) return "Il y a 1 minute"
  if (diffMinutes < 60) return `Il y a ${diffMinutes} minutes`
  if (diffHours === 1) return "Il y a 1 heure"
  if (diffHours < 24) return `Il y a ${diffHours} heures`
  if (diffDays === 1) return "Hier"
  if (diffDays < 7) return `Il y a ${diffDays} jours`
  
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
  })
}

onMounted(() => {
  fetchCategories()
  fetchPosts()
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.5s ease-out forwards;
  opacity: 0;
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

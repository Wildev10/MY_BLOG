<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
           Blog Articles
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">
          Découvrez les derniers articles de notre communauté
        </p>
      </div>

      <!-- Barre de recherche -->
      <div class="mb-8 max-w-2xl mx-auto">
        <div class="relative">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder=" Rechercher un article..."
            class="w-full px-6 py-4 pl-12 text-lg border border-gray-300 dark:border-gray-700 rounded-full focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
          />
          <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <button
            v-if="searchQuery"
            @click="clearSearch"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <p v-if="searchQuery" class="mt-2 text-sm text-gray-600 dark:text-gray-400 text-center">
          {{ posts.length }} résultat(s) pour "{{ searchQuery }}"
        </p>
      </div>

      <!-- Filtres par catégories -->
      <div class="mb-8 flex flex-wrap gap-3 justify-center">
        <button
          @click="filterByCategory(null)"
          :class="[
            'px-4 py-2 rounded-full font-medium transition',
            selectedCategory === null && !searchQuery
              ? 'bg-indigo-600 text-white'
              : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
          ]"
        >
          Toutes
        </button>
        <button
          v-for="category in categories"
          :key="category.id"
          @click="filterByCategory(category)"
          :class="[
            'px-4 py-2 rounded-full font-medium transition',
            selectedCategory?.id === category.id && !searchQuery
              ? 'text-white'
              : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
          ]"
          :style="selectedCategory?.id === category.id && !searchQuery ? { backgroundColor: category.color } : {}"
        >
          {{ category.name }} ({{ category.posts_count }})
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <svg class="animate-spin h-12 w-12 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Liste des articles -->
      <div v-else-if="posts.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="post in posts"
          :key="post.id"
          class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer border border-transparent dark:border-gray-700"
          @click="goToPost(post.id)"
        >
        
          <!-- NOUVEAU : Image de l'article -->
        <div v-if="post.image_url" class="w-full h-48 overflow-hidden">
          <img 
            :src="post.image_url" 
            :alt="post.title"
            class="w-full h-full object-cover hover:scale-105 transition duration-300"
          />
        </div>

          <div class="p-6">
            <!-- Badge catégorie -->
            <div v-if="post.category" class="mb-3">
              <span
                class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white"
                :style="{ backgroundColor: post.category.color }"
              >
                {{ post.category.name }}
              </span>
            </div>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
              {{ post.title }}
            </h3>
            <div 
              class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3 prose dark:prose-invert prose-sm"
              v-html="post.content"
            ></div>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                  <span class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                    {{ post.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>
                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">
                  {{ post.user?.name || 'Anonyme' }}
                </span>
              </div>
              
              <!-- Bouton Like -->
              <div class="flex items-center gap-4">
                <button
                  v-if="authStore.isAuthenticated"
                  @click.stop="handleLike(post)"
                  class="flex items-center gap-1 text-sm transition"
                  :class="post.is_liked ? 'text-red-500' : 'text-gray-400 dark:text-gray-500 hover:text-red-500'"
                >
                  <svg class="w-5 h-5" :fill="post.is_liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                  <span class="font-medium">{{ post.likes_count || 0 }}</span>
                </button>
                <span v-else class="flex items-center gap-1 text-gray-400 dark:text-gray-500 text-sm">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                  <span class="text-sm">{{ post.likes_count || 0 }}</span>
                </span>
                
                <span class="text-xs text-gray-500 dark:text-gray-400">
                  {{ formatDate(post.created_at) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Aucun article -->
      <div v-else class="text-center py-20">
        <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 text-lg mb-2">
          {{ searchQuery 
            ? `Aucun article trouvé pour "${searchQuery}"` 
            : selectedCategory 
              ? `Aucun article dans la catégorie "${selectedCategory.name}"` 
              : 'Aucun article pour le moment' 
          }}
        </p>
        <button
          v-if="searchQuery || selectedCategory"
          @click="clearAll"
          class="mt-4 text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-semibold"
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
const searchQuery = ref('') // NOUVEAU
const loading = ref(true)
let searchTimeout = null // NOUVEAU : Pour le debounce

const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Erreur lors du chargement des catégories:', error)
  }
}

const fetchPosts = async () => {
  loading.value = true
  try {
    const response = await getPosts()
    posts.value = response.data.data.data || response.data.data
  } catch (error) {
    console.error('Erreur lors du chargement des articles:', error)
  } finally {
    loading.value = false
  }
}

const filterByCategory = async (category) => {
  searchQuery.value = '' // Réinitialiser la recherche
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

// NOUVEAU : Fonction de recherche avec debounce
const handleSearch = () => {
  // Annuler le timeout précédent
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }

  // Si la recherche est vide, afficher tous les articles
  if (searchQuery.value.trim() === '') {
    fetchPosts()
    selectedCategory.value = null
    return
  }

  // Attendre 500ms après que l'utilisateur arrête de taper
  searchTimeout = setTimeout(async () => {
    loading.value = true
    selectedCategory.value = null

    try {
      const response = await searchPosts(searchQuery.value)
      posts.value = response.data.data.data || response.data.data
    } catch (error) {
      console.error('Erreur lors de la recherche:', error)
      posts.value = []
    } finally {
      loading.value = false
    }
  }, 500)
}


  // NOUVEAU : Fonction pour liker/unliker
  const handleLike = async (post) => {
    try {
      const response = await toggleLike(post.id)
      
      // Mettre à jour localement
      post.is_liked = response.data.liked
      post.likes_count = response.data.likes_count
    } catch (error) {
      console.error('Erreur lors du like:', error)
      if (error.response?.status === 401) {
        router.push('/login')
      }
    }
  }

// NOUVEAU : Effacer la recherche
const clearSearch = () => {
  searchQuery.value = ''
  fetchPosts()
}

// NOUVEAU : Tout effacer
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
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(async () => {
  await fetchCategories()
  await fetchPosts()
})
</script>
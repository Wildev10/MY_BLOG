<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Blog Articles
        </h1>
        <p class="text-xl text-gray-600">
          Découvrez les derniers articles de notre communauté
        </p>
      </div>

      <!-- NOUVEAU : Barre de recherche -->
      <div class="mb-8 max-w-2xl mx-auto">
        <div class="relative">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Rechercher un article..."
            class="w-full px-6 py-4 pl-12 text-lg border border-gray-300 rounded-full focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm"
          />
          <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <button
            v-if="searchQuery"
            @click="clearSearch"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <p v-if="searchQuery" class="mt-2 text-sm text-gray-600 text-center">
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
              : 'bg-white text-gray-700 hover:bg-gray-100'
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
              : 'bg-white text-gray-700 hover:bg-gray-100'
          ]"
          :style="selectedCategory?.id === category.id && !searchQuery ? { backgroundColor: category.color } : {}"
        >
          {{ category.name }} ({{ category.posts_count }})
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Liste des articles -->
      <div v-else-if="posts.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="post in posts"
          :key="post.id"
          class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden cursor-pointer"
          @click="goToPost(post.id)"
        >
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

            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
              {{ post.title }}
            </h3>
            <p class="text-gray-600 mb-4 line-clamp-3">
              {{ post.content }}
            </p>
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                  <span class="text-indigo-600 font-semibold text-sm">
                    {{ post.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>
                <span class="ml-2 text-sm text-gray-700 font-medium">
                  {{ post.user?.name || 'Anonyme' }}
                </span>
              </div>
              <span class="text-xs text-gray-500">
                {{ formatDate(post.created_at) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Aucun article -->
      <div v-else class="text-center py-20">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-500 text-lg mb-2">
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
          class="mt-4 text-indigo-600 hover:text-indigo-700 font-semibold"
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
import { getPosts, getCategories, getCategoryPosts, searchPosts } from '@/services/api'

const router = useRouter()

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
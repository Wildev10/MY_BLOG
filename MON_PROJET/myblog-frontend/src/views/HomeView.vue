<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
          Blog Articles
        </h1>
        <p class="text-xl text-gray-600">
          Découvrez les derniers articles de notre communauté
        </p>
      </div>

      <!-- Filtres par catégories -->
      <div class="mb-8 flex flex-wrap gap-3 justify-center">
        <button
          @click="filterByCategory(null)"
          :class="[
            'px-4 py-2 rounded-full font-medium transition',
            selectedCategory === null
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
            selectedCategory?.id === category.id
              ? 'text-white'
              : 'bg-white text-gray-700 hover:bg-gray-100'
          ]"
          :style="selectedCategory?.id === category.id ? { backgroundColor: category.color } : {}"
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
        <p class="text-gray-500 text-lg">
          {{ selectedCategory ? `Aucun article dans la catégorie "${selectedCategory.name}"` : 'Aucun article pour le moment' }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { getPosts, getCategories, getCategoryPosts } from '@/services/api'

const router = useRouter()

const posts = ref([])
const categories = ref([])
const selectedCategory = ref(null)
const loading = ref(true)

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
  selectedCategory.value = category
  loading.value = true

  try {
    if (category === null) {
      // Tous les articles
      await fetchPosts()
    } else {
      // Articles d'une catégorie spécifique
      const response = await getCategoryPosts(category.slug)
      posts.value = response.data.data.data || response.data.data
    }
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
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
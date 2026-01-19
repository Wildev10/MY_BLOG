<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <!-- Utilisateur non trouve -->
    <div v-else-if="notFound" class="flex flex-col items-center justify-center min-h-screen px-4">
      <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      <h1 class="mt-4 text-2xl font-bold text-gray-900">Utilisateur introuvable</h1>
      <p class="mt-2 text-gray-500">L'utilisateur @{{ username }} n'existe pas.</p>
      <router-link to="/" class="mt-6 text-indigo-600 hover:text-indigo-500">
        Retour a l'accueil
      </router-link>
    </div>

    <div v-else class="max-w-4xl mx-auto px-4 py-8">
      <!-- Header du profil -->
      <ProfileHeader :user="user" :editable="false" />

      <!-- Statistiques -->
      <ProfileStats :user="user" class="mt-6" />

      <!-- Articles de l'utilisateur -->
      <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
          Articles de {{ user.name }}
        </h2>

        <!-- Aucun article -->
        <div v-if="posts.length === 0 && !loadingPosts" class="bg-white rounded-lg shadow p-8 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article</h3>
          <p class="mt-1 text-sm text-gray-500">{{ user.name }} n'a pas encore publie d'articles.</p>
        </div>

        <!-- Liste des articles -->
        <div v-else class="grid gap-6 md:grid-cols-2">
          <article
            v-for="post in posts"
            :key="post.id"
            class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition"
          >
            <router-link :to="`/posts/${post.id}`">
              <img
                v-if="post.image_url"
                :src="post.image_url"
                :alt="post.title"
                class="w-full h-48 object-cover"
              />
              <div v-else class="w-full h-48 bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
            </router-link>

            <div class="p-4">
              <div class="flex items-center gap-2 mb-2">
                <span 
                  v-if="post.category" 
                  class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                  :style="{ backgroundColor: post.category.color + '20', color: post.category.color }"
                >
                  {{ post.category.name }}
                </span>
                <span class="text-xs text-gray-500">{{ formatDate(post.created_at) }}</span>
              </div>

              <router-link :to="`/posts/${post.id}`">
                <h3 class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition">
                  {{ post.title }}
                </h3>
              </router-link>

              <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                {{ post.excerpt || truncateContent(post.content) }}
              </p>

              <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                  {{ post.likes_count || 0 }}
                </span>
                <span class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                  </svg>
                  {{ post.comments_count || 0 }}
                </span>
              </div>
            </div>
          </article>
        </div>

        <!-- Charger plus -->
        <div v-if="hasMorePosts" class="mt-6 text-center">
          <button
            @click="loadMorePosts"
            :disabled="loadingPosts"
            class="px-6 py-2 text-sm font-medium text-indigo-600 bg-white border border-indigo-600 rounded-md hover:bg-indigo-50 disabled:opacity-50"
          >
            <span v-if="loadingPosts">Chargement...</span>
            <span v-else>Voir plus d'articles</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { getPublicProfile, getUserPosts } from '@/services/api'
import ProfileHeader from '@/components/ProfileHeader.vue'
import ProfileStats from '@/components/ProfileStats.vue'

const route = useRoute()

const username = ref('')
const loading = ref(true)
const loadingPosts = ref(false)
const notFound = ref(false)
const user = ref({})
const posts = ref([])
const currentPage = ref(1)
const hasMorePosts = ref(false)

// Charger le profil public
const loadProfile = async () => {
  try {
    loading.value = true
    notFound.value = false
    username.value = route.params.username

    const response = await getPublicProfile(username.value)
    user.value = response.data.user
    
    // Charger les premiers articles
    await loadPosts()
  } catch (error) {
    if (error.response?.status === 404) {
      notFound.value = true
    } else {
      console.error('Erreur lors du chargement du profil:', error)
    }
  } finally {
    loading.value = false
  }
}

// Charger les articles
const loadPosts = async () => {
  try {
    loadingPosts.value = true
    currentPage.value = 1
    
    const response = await getUserPosts(username.value, currentPage.value)
    posts.value = response.data.data
    hasMorePosts.value = response.data.current_page < response.data.last_page
  } catch (error) {
    console.error('Erreur lors du chargement des articles:', error)
  } finally {
    loadingPosts.value = false
  }
}

// Charger plus d'articles
const loadMorePosts = async () => {
  try {
    loadingPosts.value = true
    currentPage.value++
    
    const response = await getUserPosts(username.value, currentPage.value)
    posts.value = [...posts.value, ...response.data.data]
    hasMorePosts.value = response.data.current_page < response.data.last_page
  } catch (error) {
    console.error('Erreur lors du chargement des articles:', error)
    currentPage.value--
  } finally {
    loadingPosts.value = false
  }
}

// Tronquer le contenu
const truncateContent = (content) => {
  if (!content) return ''
  const stripped = content.replace(/<[^>]*>/g, '')
  return stripped.length > 120 ? stripped.substring(0, 120) + '...' : stripped
}

// Formater la date
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

// Recharger si le username change
watch(() => route.params.username, (newUsername) => {
  if (newUsername) {
    loadProfile()
  }
})

onMounted(() => {
  loadProfile()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

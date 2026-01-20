<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <svg class="animate-spin h-8 w-8 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <!-- Utilisateur non trouve -->
    <div v-else-if="notFound" class="flex flex-col items-center justify-center min-h-screen px-4">
      <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      <h1 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-zinc-50">Utilisateur introuvable</h1>
      <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">L'utilisateur @{{ username }} n'existe pas.</p>
      <router-link to="/" class="mt-4 text-sm text-zinc-600 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-50 transition">
        ← Retour à l'accueil
      </router-link>
    </div>

    <div v-else class="max-w-4xl mx-auto px-4 py-8">
      <!-- Header du profil -->
      <ProfileHeader :user="user" :editable="false" />

      <!-- Statistiques -->
      <ProfileStats :user="user" class="mt-6" />

      <!-- Articles de l'utilisateur -->
      <div class="mt-8">
        <h2 class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-4">
          Articles de {{ user.name }}
        </h2>

        <!-- Aucun article -->
        <div v-if="posts.length === 0 && !loadingPosts" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-8 text-center">
          <svg class="mx-auto h-10 w-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-50">Aucun article</h3>
          <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ user.name }} n'a pas encore publié d'articles.</p>
        </div>

        <!-- Liste des articles -->
        <div v-else class="space-y-3">
          <article
            v-for="post in posts"
            :key="post.id"
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden hover:border-zinc-300 dark:hover:border-zinc-700 transition"
          >
            <router-link :to="'/posts/' + post.id" class="flex">
              <div v-if="post.image_url" class="w-32 h-24 flex-shrink-0">
                <img
                  :src="post.image_url"
                  :alt="post.title"
                  class="w-full h-full object-cover"
                />
              </div>
              <div v-else class="w-32 h-24 flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              
              <div class="flex-1 p-4">
                <div class="flex items-center gap-2 mb-1">
                  <span 
                    v-if="post.category" 
                    class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium"
                    :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
                  >
                    {{ post.category.name }}
                  </span>
                  <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ formatDate(post.created_at) }}</span>
                </div>

                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 hover:text-zinc-600 dark:hover:text-zinc-300 transition line-clamp-1">
                  {{ post.title }}
                </h3>

                <div class="mt-2 flex items-center gap-3 text-xs text-zinc-400 dark:text-zinc-500">
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
              </div>
            </router-link>
          </article>
        </div>

        <!-- Charger plus -->
        <div v-if="hasMorePosts" class="mt-6 text-center">
          <button
            @click="loadMorePosts"
            :disabled="loadingPosts"
            class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-md hover:bg-zinc-50 dark:hover:bg-zinc-800 disabled:opacity-50 transition"
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

const loadProfile = async () => {
  try {
    loading.value = true
    notFound.value = false
    username.value = route.params.username

    const response = await getPublicProfile(username.value)
    user.value = response.data.user
    
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

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

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
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

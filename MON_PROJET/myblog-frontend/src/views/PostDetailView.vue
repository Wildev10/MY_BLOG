<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-20">
        <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <!-- Article -->
      <article v-else-if="post" class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-12">
          <button
            @click="$router.push('/')"
            class="mb-6 text-white hover:text-indigo-100 transition flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour
          </button>

          <!-- NOUVEAU : Badge catégorie -->
          <div v-if="post.category" class="mb-4">
            <span
              class="inline-block px-4 py-2 rounded-full text-sm font-semibold text-white bg-white/20 backdrop-blur-sm"
            >
              {{ post.category.name }}
            </span>
          </div>

          <h1 class="text-4xl font-bold text-white mb-4">
            {{ post.title }}
          </h1>
          <div class="flex items-center text-white">
            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
              <span class="text-white font-semibold">
                {{ post.user?.name?.charAt(0) || '?' }}
              </span>
            </div>
            <div>
              <p class="font-semibold">{{ post.user?.name || 'Anonyme' }}</p>
              <p class="text-sm text-indigo-100">{{ formatDate(post.created_at) }}</p>
            </div>
          </div>
        </div>

        <!-- NOUVEAU : Bouton Like -->
    <div class="mt-6 flex items-center gap-4">
      <button
        v-if="authStore.isAuthenticated"
        @click="handleLike"
        class="flex items-center gap-2 px-6 py-3 rounded-full font-semibold transition"
        :class="post.is_liked 
          ? 'bg-red-500 text-white hover:bg-red-600' 
          : 'bg-white/20 text-white hover:bg-white/30'"
      >
        <svg class="w-6 h-6" :fill="post.is_liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <span>{{ post.is_liked ? 'J\'aime' : 'Aimer' }}</span>
        <span class="bg-white/20 px-2 py-1 rounded-full text-sm">
          {{ post.likes_count || 0 }}
        </span>
      </button>
      
      <span v-else class="flex items-center gap-2 text-white/70 text-sm">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        {{ post.likes_count || 0 }} j'aime
      </span>
    </div>

     
        <!-- NOUVEAU : Image de l'article -->
        <div v-if="post.image_url" class="w-full">
          <img 
            :src="post.image_url" 
            :alt="post.title"
            class="w-full max-h-96 object-cover"
          />
        </div>
       <!-- Contenu -->
        <div class="px-8 py-12">
          <div 
            class="prose prose-lg dark:prose-invert max-w-none text-gray-900 dark:text-white"
            v-html="post.content"
          ></div>
        </div>

           <!-- NOUVEAU : Boutons de partage -->
        <div class="px-8 pb-8 border-b border-gray-200 dark:border-gray-700">
          <ShareButtons 
            :title="post.title"
            :url="shareUrl"
          />
        </div>

        <!-- Actions (si c'est l'auteur) -->
        <div v-if="isAuthor" class="px-8 py-6 bg-gray-50 border-t border-gray-200">
          <div class="flex gap-4">
            <button
              @click="editPost"
              class="flex-1 bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition"
            >
              Modifier
            </button>
            <button
              @click="confirmDelete"
              class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition"
            >
              Supprimer
            </button>
          </div>
        </div>
      </article>

      <!-- NOUVEAU : Section Commentaires -->
      <CommentSection v-if="post" :key="`comments-${post.id}-${commentKey}`" :post-id="post.id" class="mt-8" />

      <!-- Erreur -->
      <div v-else class="text-center py-20">
        <p class="text-gray-500 text-lg">Article introuvable</p>
        <button
          @click="$router.push('/')"
          class="mt-4 text-indigo-600 hover:text-indigo-700 font-semibold"
        >
          Retour à l'accueil
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getPost, deletePost, toggleLike } from '@/services/api'
import CommentSection from '@/components/CommentSection.vue' 
import ShareButtons from '@/components/ShareButtons.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const post = ref(null)
const loading = ref(true)
const commentKey = ref(0) // Clé pour forcer le rechargement des commentaires

const isAuthor = computed(() => {
  return authStore.user && post.value && authStore.user.id === post.value.user_id
})

const fetchPost = async () => {
  loading.value = true
  try {
    const response = await getPost(route.params.id)
    post.value = response.data.data
    // Incrémenter la clé pour forcer le rechargement des commentaires
    commentKey.value++
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

// Recharger le post quand l'ID dans la route change
watch(() => route.params.id, (newId, oldId) => {
  if (newId && newId !== oldId) {
    fetchPost()
  }
})

// Recharger aussi quand on revient sur cette page (même ID)
watch(() => route.fullPath, () => {
  if (route.name === 'post-detail') {
    fetchPost()
  }
}, { immediate: false })

const editPost = () => {
  router.push(`/posts/${post.value.id}/edit`)
}

const confirmDelete = () => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
    handleDelete()
  }
}

const handleDelete = async () => {
  try {
    await deletePost(post.value.id)
    router.push('/')
  } catch (error) {
    alert('Erreur lors de la suppression')
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  fetchPost()
})

// NOUVEAU : Fonction like
const handleLike = async () => {
  try {
    const response = await toggleLike(post.value.id)
    post.value.is_liked = response.data.liked
    post.value.likes_count = response.data.likes_count
  } catch (error) {
    console.error('Erreur:', error)
  }
}

// NOUVEAU : URL de partage
const shareUrl = computed(() => {
  if (typeof window !== 'undefined') {
    return window.location.origin + '/posts/' + route.params.id
  }
  return ''
})
</script>
<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
    </div>

    <div v-else class="max-w-4xl mx-auto px-4 py-8">
      <!-- Header du profil -->
      <ProfileHeader
        :user="user"
        :editable="true"
        @avatar-upload="handleAvatarUpload"
        @avatar-delete="handleAvatarDelete"
      />

      <!-- Statistiques -->
      <ProfileStats :user="user" class="mt-6" />

      <!-- Articles recents -->
      <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-gray-900">Mes derniers articles</h2>
          <router-link 
            to="/my-posts" 
            class="text-indigo-600 hover:text-indigo-500 text-sm font-medium"
          >
            Voir tous mes articles
          </router-link>
        </div>

        <div v-if="recentPosts.length === 0" class="bg-white rounded-lg shadow p-6 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article</h3>
          <p class="mt-1 text-sm text-gray-500">Commencez par creer votre premier article.</p>
          <div class="mt-6">
            <router-link
              to="/posts/create"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Nouveau article
            </router-link>
          </div>
        </div>

        <div v-else class="bg-white rounded-lg shadow divide-y divide-gray-200">
          <article
            v-for="post in recentPosts"
            :key="post.id"
            class="p-4 hover:bg-gray-50 transition"
          >
            <router-link :to="`/posts/${post.id}`" class="block">
              <div class="flex items-start gap-4">
                <img
                  v-if="post.image_url"
                  :src="post.image_url"
                  :alt="post.title"
                  class="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                />
                <div class="flex-1 min-w-0">
                  <h3 class="text-lg font-medium text-gray-900 truncate">
                    {{ post.title }}
                  </h3>
                  <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                    {{ post.excerpt || truncateContent(post.content) }}
                  </p>
                  <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                    <span v-if="post.category" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" :style="{ backgroundColor: post.category.color + '20', color: post.category.color }">
                      {{ post.category.name }}
                    </span>
                    <span>{{ formatDate(post.created_at) }}</span>
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
              </div>
            </router-link>
          </article>
        </div>
      </div>

      <!-- Zone de danger -->
      <div class="mt-8 bg-white rounded-lg shadow">
        <div class="p-6">
          <h2 class="text-lg font-medium text-red-600">Zone de danger</h2>
          <p class="mt-1 text-sm text-gray-500">
            Ces actions sont irreversibles. Veuillez proceder avec prudence.
          </p>
          <div class="mt-4">
            <button
              @click="showDeleteModal = true"
              class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
            >
              Supprimer mon compte
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <Teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
          <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showDeleteModal = false"></div>
          
          <div class="relative bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
            <div class="flex items-center gap-4">
              <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-medium text-gray-900">Supprimer le compte</h3>
                <p class="mt-1 text-sm text-gray-500">
                  Cette action supprimera definitivement votre compte et toutes vos donnees.
                </p>
              </div>
            </div>

            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700">
                Tapez votre mot de passe pour confirmer
              </label>
              <input
                v-model="deletePassword"
                type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                placeholder="Votre mot de passe"
              />
            </div>

            <div class="mt-6 flex gap-3 justify-end">
              <button
                @click="showDeleteModal = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
              >
                Annuler
              </button>
              <button
                @click="handleDeleteAccount"
                :disabled="!deletePassword || deleting"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="deleting">Suppression...</span>
                <span v-else>Supprimer definitivement</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getProfile, uploadAvatar, deleteAvatar, deleteAccount } from '@/services/api'
import ProfileHeader from '@/components/ProfileHeader.vue'
import ProfileStats from '@/components/ProfileStats.vue'

const router = useRouter()
const authStore = useAuthStore()

const loading = ref(true)
const user = ref({})
const recentPosts = ref([])
const showDeleteModal = ref(false)
const deletePassword = ref('')
const deleting = ref(false)

// Charger le profil
const loadProfile = async () => {
  try {
    loading.value = true
    const response = await getProfile()
    user.value = response.data.user
    recentPosts.value = response.data.recent_posts || []
  } catch (error) {
    console.error('Erreur lors du chargement du profil:', error)
  } finally {
    loading.value = false
  }
}

// Gerer l'upload d'avatar
const handleAvatarUpload = async (file) => {
  try {
    const response = await uploadAvatar(file)
    user.value.avatar = response.data.avatar
    user.value.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: user.value.avatar, avatar_url: user.value.avatar_url }
  } catch (error) {
    console.error('Erreur lors de l\'upload:', error)
    alert(error.response?.data?.message || 'Erreur lors de l\'upload de l\'avatar')
  }
}

// Supprimer l'avatar
const handleAvatarDelete = async () => {
  if (!confirm('Voulez-vous vraiment supprimer votre avatar ?')) return
  
  try {
    const response = await deleteAvatar()
    user.value.avatar = null
    user.value.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: null, avatar_url: user.value.avatar_url }
  } catch (error) {
    console.error('Erreur lors de la suppression:', error)
    alert('Erreur lors de la suppression de l\'avatar')
  }
}

// Supprimer le compte
const handleDeleteAccount = async () => {
  try {
    deleting.value = true
    await deleteAccount(deletePassword.value)
    authStore.logout()
    router.push('/')
  } catch (error) {
    console.error('Erreur lors de la suppression:', error)
    alert(error.response?.data?.message || 'Erreur lors de la suppression du compte')
  } finally {
    deleting.value = false
  }
}

// Tronquer le contenu
const truncateContent = (content) => {
  if (!content) return ''
  const stripped = content.replace(/<[^>]*>/g, '')
  return stripped.length > 150 ? stripped.substring(0, 150) + '...' : stripped
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

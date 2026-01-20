<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors">
    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <div v-else class="max-w-3xl mx-auto px-4 py-12">
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
          <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Mes derniers articles</h2>
          <router-link 
            to="/my-posts" 
            class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition"
          >
            Voir tout
          </router-link>
        </div>

        <div v-if="recentPosts.length === 0" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-8 text-center">
          <svg class="mx-auto h-10 w-10 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">Aucun article pour le moment</p>
          <router-link
            to="/create"
            class="inline-block mt-4 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 rounded-md hover:bg-zinc-800 dark:hover:bg-zinc-200 transition"
          >
            Creer un article
          </router-link>
        </div>

        <div v-else class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg divide-y divide-zinc-100 dark:divide-zinc-800">
          <article
            v-for="post in recentPosts"
            :key="post.id"
            class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition"
          >
            <router-link :to="'/posts/' + post.id" class="flex items-start gap-4">
              <img
                v-if="post.image_url"
                :src="post.image_url"
                :alt="post.title"
                class="w-16 h-16 object-cover rounded-md flex-shrink-0"
              />
              <div class="flex-1 min-w-0">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 truncate">
                  {{ post.title }}
                </h3>
                <div class="mt-1 flex items-center gap-3 text-xs text-zinc-400">
                  <span 
                    v-if="post.category" 
                    class="px-1.5 py-0.5 rounded"
                    :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
                  >
                    {{ post.category.name }}
                  </span>
                  <span>{{ formatDate(post.created_at) }}</span>
                  <span class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ post.likes_count || 0 }}
                  </span>
                </div>
              </div>
            </router-link>
          </article>
        </div>
      </div>

      <!-- Zone de danger -->
      <div class="mt-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6">
        <h2 class="text-sm font-medium text-red-600 dark:text-red-400">Zone de danger</h2>
        <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
          Actions irreversibles. Procedez avec prudence.
        </p>
        <button
          @click="showDeleteModal = true"
          class="mt-4 px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition"
        >
          Supprimer mon compte
        </button>
      </div>
    </div>

    <!-- Modal de confirmation -->
    <Teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
          <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showDeleteModal = false"></div>
          
          <div class="relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Supprimer le compte</h3>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
              Cette action supprimera definitivement votre compte et toutes vos donnees.
            </p>

            <div class="mt-4">
              <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
                Confirmez avec votre mot de passe
              </label>
              <input
                v-model="deletePassword"
                type="password"
                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                placeholder="Mot de passe"
              />
            </div>

            <div class="mt-6 flex gap-3 justify-end">
              <button
                @click="showDeleteModal = false"
                class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700 rounded-md hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
              >
                Annuler
              </button>
              <button
                @click="handleDeleteAccount"
                :disabled="!deletePassword || deleting"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
              >
                {{ deleting ? 'Suppression...' : 'Supprimer' }}
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

const loadProfile = async () => {
  try {
    loading.value = true
    const response = await getProfile()
    user.value = response.data.user
    recentPosts.value = response.data.recent_posts || []
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const handleAvatarUpload = async (file) => {
  try {
    const response = await uploadAvatar(file)
    user.value.avatar = response.data.avatar
    user.value.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: user.value.avatar, avatar_url: user.value.avatar_url }
  } catch (error) {
    console.error('Erreur:', error)
    alert(error.response?.data?.message || "Erreur lors de l'upload")
  }
}

const handleAvatarDelete = async () => {
  if (!confirm('Supprimer votre avatar ?')) return
  
  try {
    const response = await deleteAvatar()
    user.value.avatar = null
    user.value.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: null, avatar_url: user.value.avatar_url }
  } catch (error) {
    console.error('Erreur:', error)
    alert("Erreur lors de la suppression")
  }
}

const handleDeleteAccount = async () => {
  try {
    deleting.value = true
    await deleteAccount(deletePassword.value)
    authStore.logout()
    router.push('/')
  } catch (error) {
    console.error('Erreur:', error)
    alert(error.response?.data?.message || 'Erreur lors de la suppression')
  } finally {
    deleting.value = false
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

onMounted(() => {
  loadProfile()
})
</script>

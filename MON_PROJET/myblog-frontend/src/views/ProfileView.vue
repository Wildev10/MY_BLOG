<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-0 left-1/3 w-[600px] h-[600px] bg-gradient-to-br from-purple-100/20 to-pink-100/20 dark:from-purple-900/5 dark:to-pink-900/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-gradient-to-tr from-blue-100/20 to-cyan-100/20 dark:from-blue-900/5 dark:to-cyan-900/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex flex-col items-center justify-center min-h-screen gap-4">
      <div class="w-16 h-16 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
        <svg class="animate-spin w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <p class="text-slate-500 dark:text-slate-400">Chargement de votre profil...</p>
    </div>

    <div v-else class="relative max-w-4xl mx-auto px-4 sm:px-6 py-12">
      <!-- Header du profil -->
      <ProfileHeader
        :user="user"
        :editable="true"
        @avatar-upload="handleAvatarUpload"
        @avatar-delete="handleAvatarDelete"
      />

      <!-- Statistiques -->
      <ProfileStats :user="user" class="mt-8" />

      <!-- Articles récents -->
      <div class="mt-12">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-3">
            <div class="p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl">
              <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Mes derniers articles</h2>
          </div>
          <router-link 
            to="/my-posts" 
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm group"
          >
            Voir tout
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </router-link>
        </div>

        <!-- Empty state -->
        <div v-if="recentPosts.length === 0" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-12 text-center shadow-sm">
          <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 rounded-2xl flex items-center justify-center">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Aucun article publié</h3>
          <p class="text-slate-500 dark:text-slate-400 mb-6">Partagez vos idées avec le monde entier</p>
          <router-link
            to="/create"
            class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-slate-900 dark:bg-white dark:text-slate-900 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors shadow-lg shadow-slate-900/20 dark:shadow-none"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Rédiger mon premier article
          </router-link>
        </div>

        <!-- Posts list -->
        <div v-else class="space-y-4">
          <article
            v-for="(post, index) in recentPosts"
            :key="post.id"
            class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 hover:border-slate-300 dark:hover:border-slate-700 transition-all shadow-sm hover:shadow-lg hover:shadow-slate-200/50 dark:hover:shadow-none"
            :style="{ animationDelay: `${index * 100}ms` }"
          >
            <router-link :to="'/posts/' + post.id" class="flex gap-5">
              <div class="flex-shrink-0 w-28 h-28 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 overflow-hidden">
                <img
                  v-if="post.image_url"
                  :src="post.image_url"
                  :alt="post.title"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
              </div>
              
              <div class="flex-1 min-w-0 py-1 flex flex-col justify-between">
                <div>
                  <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ post.title }}
                  </h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 line-clamp-2">
                    {{ post.excerpt || 'Aucun extrait disponible' }}
                  </p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 text-xs font-medium text-slate-400 mt-3">
                  <span 
                    v-if="post.category" 
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg"
                    :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :style="{ backgroundColor: post.category.color }"></span>
                    {{ post.category.name }}
                  </span>
                  <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ formatDate(post.created_at) }}
                  </span>
                  <span class="flex items-center gap-1.5 text-red-400">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ post.likes_count || 0 }}
                  </span>
                </div>
              </div>

              <!-- Arrow -->
              <div class="hidden sm:flex items-center">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-slate-900 dark:group-hover:bg-white transition-colors">
                  <svg class="w-5 h-5 text-slate-400 group-hover:text-white dark:group-hover:text-slate-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </div>
              </div>
            </router-link>
          </article>
        </div>
      </div>

      <!-- Zone de danger -->
      <div class="mt-16 pt-10 border-t border-slate-200 dark:border-slate-800">
        <div class="flex items-center gap-3 mb-4">
          <div class="p-2.5 bg-red-100 dark:bg-red-900/30 rounded-xl">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
          </div>
          <h2 class="text-lg font-bold text-red-600 dark:text-red-500">Zone de danger</h2>
        </div>
        <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <div>
            <p class="text-base font-semibold text-red-900 dark:text-red-200">Supprimer mon compte</p>
            <p class="text-sm text-red-700 dark:text-red-400 mt-1">
              Cette action est irréversible et supprimera toutes vos données.
            </p>
          </div>
          <button
            @click="showDeleteModal = true"
            class="flex-shrink-0 px-5 py-2.5 text-sm font-bold text-red-600 dark:text-red-400 bg-white dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors shadow-sm flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Supprimer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation -->
    <Teleport to="body">
      <Transition
        enter-active-class="duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
          <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <Transition
              enter-active-class="duration-200 ease-out"
              enter-from-class="opacity-0 scale-95"
              enter-to-class="opacity-100 scale-100"
              leave-active-class="duration-150 ease-in"
              leave-from-class="opacity-100 scale-100"
              leave-to-class="opacity-0 scale-95"
            >
              <div v-if="showDeleteModal" class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-md w-full p-8 shadow-2xl">
                <div class="text-center mb-6">
                  <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-900/20 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                  </div>
                  <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Supprimer le compte ?</h3>
                  <p class="mt-3 text-slate-500 dark:text-slate-400">
                    Cette action est irréversible. Entrez votre mot de passe pour confirmer.
                  </p>
                </div>

                <div class="space-y-4">
                  <div class="relative">
                    <input
                      v-model="deletePassword"
                      type="password"
                      class="w-full px-4 py-3.5 pl-12 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent focus:bg-white dark:focus:bg-slate-900 transition-all"
                      placeholder="Votre mot de passe"
                    />
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                  </div>

                  <div class="grid grid-cols-2 gap-3 pt-2">
                    <button
                      @click="showDeleteModal = false"
                      class="px-5 py-3.5 text-sm font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
                    >
                      Annuler
                    </button>
                    <button
                      @click="handleDeleteAccount"
                      :disabled="!deletePassword || deleting"
                      class="relative px-5 py-3.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-lg shadow-red-600/30 overflow-hidden"
                    >
                      <span :class="{ 'opacity-0': deleting }">Confirmer</span>
                      <span v-if="deleting" class="absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </Transition>
          </div>
        </div>
      </Transition>
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

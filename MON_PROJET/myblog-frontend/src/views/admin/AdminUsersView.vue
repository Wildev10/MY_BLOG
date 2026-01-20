<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 py-8 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <AdminSidebar />
        </div>

        <!-- Contenu principal -->
        <div class="lg:col-span-3">
          <!-- Header -->
          <div class="mb-6">
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
              Utilisateurs
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              Gerez les comptes utilisateurs de la plateforme
            </p>
          </div>

          <!-- Filtres -->
          <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Recherche -->
              <div>
                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
                  Rechercher
                </label>
                <input
                  v-model="filters.search"
                  @input="debouncedSearch"
                  type="text"
                  placeholder="Nom ou email..."
                  class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                />
              </div>

              <!-- Filtre par role -->
              <div>
                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
                  Role
                </label>
                <select
                  v-model="filters.role"
                  @change="fetchUsers"
                  class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                >
                  <option value="">Tous</option>
                  <option value="admin">Administrateurs</option>
                  <option value="user">Utilisateurs</option>
                </select>
              </div>

              <!-- Filtre par statut -->
              <div>
                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
                  Statut
                </label>
                <select
                  v-model="filters.is_banned"
                  @change="fetchUsers"
                  class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                >
                  <option value="">Tous</option>
                  <option value="0">Actifs</option>
                  <option value="1">Bannis</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="flex justify-center items-center py-20">
            <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <!-- Liste des utilisateurs -->
          <div v-else class="space-y-3">
            <div v-if="users.length === 0" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-8 text-center">
              <p class="text-sm text-zinc-500 dark:text-zinc-400">Aucun utilisateur trouve</p>
            </div>

            <div
              v-for="user in users"
              :key="user.id"
              class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4"
              :class="{ 'opacity-60': user.is_banned }"
            >
              <div class="flex items-center gap-4">
                <!-- Avatar -->
                <div class="h-10 w-10 rounded-full overflow-hidden bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                  <img 
                    v-if="user.avatar_url" 
                    :src="user.avatar_url" 
                    :alt="user.name"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="text-zinc-600 dark:text-zinc-400 font-medium text-sm">
                    {{ user.name?.charAt(0) || '?' }}
                  </span>
                </div>

                <!-- Infos utilisateur -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-0.5">
                    <h3 class="font-medium text-zinc-900 dark:text-zinc-50 text-sm">
                      {{ user.name }}
                    </h3>
                    <span
                      v-if="user.role === 'admin'"
                      class="px-1.5 py-0.5 text-xs font-medium rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400"
                    >
                      Admin
                    </span>
                    <span
                      v-if="user.is_banned"
                      class="px-1.5 py-0.5 text-xs font-medium rounded bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400"
                    >
                      Banni
                    </span>
                  </div>
                  <p class="text-zinc-500 dark:text-zinc-400 text-xs">{{ user.email }}</p>
                  <div class="flex gap-4 mt-1 text-xs text-zinc-400">
                    <span>{{ user.posts_count || 0 }} articles</span>
                    <span>{{ user.comments_count || 0 }} commentaires</span>
                    <span>{{ formatDate(user.created_at) }}</span>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-1.5 flex-shrink-0">
                  <button
                    @click="toggleAdmin(user)"
                    :disabled="user.id === currentUserId"
                    class="p-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="user.role === 'admin' 
                      ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700'
                      : 'hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-400'"
                    :title="user.role === 'admin' ? 'Retrograder' : 'Promouvoir admin'"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                  </button>

                  <button
                    @click="toggleBan(user)"
                    :disabled="user.id === currentUserId || user.role === 'admin'"
                    class="p-2 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="user.is_banned 
                      ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30'
                      : 'hover:bg-red-50 dark:hover:bg-red-900/20 text-zinc-400 hover:text-red-600 dark:hover:text-red-400'"
                    :title="user.is_banned ? 'Debannir' : 'Bannir'"
                  >
                    <svg v-if="user.is_banned" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="pagination.total > pagination.per_page" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-3 flex items-center justify-between">
              <p class="text-xs text-zinc-500 dark:text-zinc-400">
                Page {{ pagination.current_page }} / {{ pagination.last_page }}
              </p>
              <div class="flex gap-2">
                <button
                  @click="changePage(pagination.current_page - 1)"
                  :disabled="pagination.current_page <= 1"
                  class="px-3 py-1.5 text-xs font-medium rounded-md border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                  Precedent
                </button>
                <button
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="pagination.current_page >= pagination.last_page"
                  class="px-3 py-1.5 text-xs font-medium rounded-md border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                  Suivant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { getAdminUsers, toggleUserAdmin, toggleUserBan } from '@/services/api'
import { useAuthStore } from '@/stores/auth'
import AdminSidebar from '@/components/AdminSidebar.vue'

const authStore = useAuthStore()
const currentUserId = computed(() => authStore.user?.id)

const users = ref([])
const loading = ref(true)

const filters = reactive({
  search: '',
  role: '',
  is_banned: ''
})

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0
})

let searchTimeout = null

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchUsers()
  }, 500)
}

const fetchUsers = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      search: filters.search || undefined,
      role: filters.role || undefined,
      is_banned: filters.is_banned || undefined
    }
    
    Object.keys(params).forEach(key => {
      if (params[key] === undefined || params[key] === '') {
        delete params[key]
      }
    })

    const response = await getAdminUsers(params)
    const data = response.data.data
    
    users.value = data.data
    pagination.current_page = data.current_page
    pagination.last_page = data.last_page
    pagination.per_page = data.per_page
    pagination.total = data.total
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page) {
    fetchUsers(page)
  }
}

const toggleAdmin = async (user) => {
  if (user.id === currentUserId.value) return
  
  try {
    const response = await toggleUserAdmin(user.id)
    user.role = response.data.data.role
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const toggleBan = async (user) => {
  if (user.id === currentUserId.value || user.role === 'admin') return
  
  try {
    const response = await toggleUserBan(user.id)
    user.is_banned = response.data.data.is_banned
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

onMounted(() => {
  fetchUsers()
})
</script>

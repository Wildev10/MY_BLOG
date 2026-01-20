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
              Articles
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              Moderez les articles publies sur la plateforme
            </p>
          </div>

          <!-- Filtres -->
          <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
                  Rechercher
                </label>
                <input
                  v-model="filters.search"
                  @input="debouncedSearch"
                  type="text"
                  placeholder="Titre ou contenu..."
                  class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                />
              </div>
              <div>
                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
                  Categorie
                </label>
                <select
                  v-model="filters.category"
                  @change="fetchPosts"
                  class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                >
                  <option value="">Toutes</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
                  Trier par
                </label>
                <select
                  v-model="filters.sort"
                  @change="fetchPosts"
                  class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                >
                  <option value="latest">Plus recents</option>
                  <option value="oldest">Plus anciens</option>
                  <option value="popular">Plus populaires</option>
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

          <!-- Liste des articles -->
          <div v-else class="space-y-3">
            <div v-if="posts.length === 0" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-8 text-center">
              <p class="text-sm text-zinc-500 dark:text-zinc-400">Aucun article trouve</p>
            </div>

            <div
              v-for="post in posts"
              :key="post.id"
              class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4"
            >
              <div class="flex items-start gap-4">
                <!-- Image -->
                <div v-if="post.image" class="w-16 h-16 flex-shrink-0">
                  <img
                    :src="post.image"
                    :alt="post.title"
                    class="w-full h-full object-cover rounded-md"
                  />
                </div>
                <div v-else class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-md flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>

                <!-- Contenu -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-medium text-zinc-900 dark:text-zinc-50 text-sm truncate">
                      {{ post.title }}
                    </h3>
                    <span
                      v-if="post.category"
                      class="px-1.5 py-0.5 text-xs rounded"
                      :style="{ backgroundColor: post.category.color + '15', color: post.category.color }"
                    >
                      {{ post.category.name }}
                    </span>
                  </div>

                  <p class="text-zinc-500 dark:text-zinc-400 text-xs mb-2 line-clamp-1">
                    {{ post.excerpt || stripHtml(post.content)?.substring(0, 100) + '...' }}
                  </p>

                  <!-- Metadonnees -->
                  <div class="flex flex-wrap items-center gap-3 text-xs text-zinc-400">
                    <span>{{ post.user?.name || 'Anonyme' }}</span>
                    <span>{{ formatDate(post.created_at) }}</span>
                    <span class="flex items-center gap-1">
                      <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                      </svg>
                      {{ post.likes_count || 0 }}
                    </span>
                    <span class="flex items-center gap-1">
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                      </svg>
                      {{ post.comments_count || 0 }}
                    </span>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-1.5 flex-shrink-0">
                  <button
                    @click="viewPost(post.id)"
                    class="p-2 rounded-md text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-300 transition"
                    title="Voir"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                  <button
                    @click="confirmDelete(post)"
                    class="p-2 rounded-md text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition"
                    title="Supprimer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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

  <!-- Modal de confirmation -->
  <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6 max-w-sm w-full">
      <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
        Supprimer l'article
      </h3>
      <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">
        Voulez-vous vraiment supprimer "{{ postToDelete?.title }}" ? Cette action est irreversible.
      </p>
      <div class="flex gap-3 justify-end">
        <button
          @click="showDeleteModal = false"
          class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition"
        >
          Annuler
        </button>
        <button
          @click="deletePost"
          :disabled="deleting"
          class="px-3 py-2 text-sm font-medium bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50 transition"
        >
          {{ deleting ? 'Suppression...' : 'Supprimer' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { getAdminPosts, deleteAdminPost, getCategories } from '@/services/api'
import AdminSidebar from '@/components/AdminSidebar.vue'

const router = useRouter()

const posts = ref([])
const categories = ref([])
const loading = ref(true)
const deleting = ref(false)
const showDeleteModal = ref(false)
const postToDelete = ref(null)

const filters = reactive({
  search: '',
  category: '',
  sort: 'latest'
})

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0
})

let searchTimeout = null

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    fetchPosts()
  }, 500)
}

const fetchPosts = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      search: filters.search,
      category_id: filters.category,
      sort: filters.sort
    }
    const response = await getAdminPosts(params)
    const paginatedData = response.data.data
    posts.value = paginatedData.data || []
    pagination.current_page = paginatedData.current_page || 1
    pagination.last_page = paginatedData.last_page || 1
    pagination.per_page = paginatedData.per_page || 10
    pagination.total = paginatedData.total || 0
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data || response.data
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= pagination.last_page) {
    fetchPosts(page)
  }
}

const viewPost = (id) => {
  router.push(`/posts/${id}`)
}

const confirmDelete = (post) => {
  postToDelete.value = post
  showDeleteModal.value = true
}

const deletePost = async () => {
  if (!postToDelete.value) return
  
  deleting.value = true
  try {
    await deleteAdminPost(postToDelete.value.id)
    posts.value = posts.value.filter(p => p.id !== postToDelete.value.id)
    showDeleteModal.value = false
    postToDelete.value = null
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    deleting.value = false
  }
}

const stripHtml = (html) => {
  if (!html) return ''
  return html.replace(/<[^>]*>/g, '')
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
  fetchPosts()
  fetchCategories()
})
</script>

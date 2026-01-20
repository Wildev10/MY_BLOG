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
              Commentaires
            </h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
              Moderez les commentaires de la plateforme
            </p>
          </div>

          <!-- Recherche -->
          <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 mb-6">
            <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
              Rechercher
            </label>
            <input
              v-model="filters.search"
              @input="debouncedSearch"
              type="text"
              placeholder="Rechercher dans les commentaires..."
              class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
            />
          </div>

          <!-- Loading -->
          <div v-if="loading" class="flex justify-center items-center py-20">
            <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <!-- Liste des commentaires -->
          <div v-else class="space-y-3">
            <div v-if="comments.length === 0" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-8 text-center">
              <p class="text-sm text-zinc-500 dark:text-zinc-400">Aucun commentaire trouve</p>
            </div>

            <div
              v-for="comment in comments"
              :key="comment.id"
              class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4"
            >
              <div class="flex items-start gap-3">
                <!-- Avatar -->
                <div class="h-8 w-8 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center flex-shrink-0">
                  <span class="text-zinc-600 dark:text-zinc-400 font-medium text-xs">
                    {{ comment.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>

                <!-- Contenu -->
                <div class="flex-1 min-w-0">
                  <!-- Infos utilisateur -->
                  <div class="flex items-center gap-2 mb-1">
                    <span class="font-medium text-zinc-900 dark:text-zinc-50 text-sm">
                      {{ comment.user?.name || 'Anonyme' }}
                    </span>
                    <span class="text-xs text-zinc-400">
                      {{ formatDate(comment.created_at) }}
                    </span>
                  </div>

                  <!-- Commentaire -->
                  <p class="text-sm text-zinc-600 dark:text-zinc-300 mb-2 whitespace-pre-wrap">
                    {{ comment.content }}
                  </p>

                  <!-- Article lie -->
                  <div class="flex items-center gap-1.5 text-xs text-zinc-400">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <button
                      @click="viewPost(comment.post?.id)"
                      class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:underline transition"
                    >
                      {{ comment.post?.title || 'Article supprime' }}
                    </button>
                  </div>
                </div>

                <!-- Actions -->
                <button
                  @click="confirmDelete(comment)"
                  class="p-2 rounded-md text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition flex-shrink-0"
                  title="Supprimer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
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
                  :disabled="pagination.current_page === 1"
                  class="px-3 py-1.5 text-xs font-medium rounded-md border border-zinc-200 dark:border-zinc-700 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 disabled:opacity-50 disabled:cursor-not-allowed transition"
                >
                  Precedent
                </button>
                <button
                  @click="changePage(pagination.current_page + 1)"
                  :disabled="pagination.current_page === pagination.last_page"
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { getAdminComments, deleteAdminComment } from '@/services/api'
import AdminSidebar from '@/components/AdminSidebar.vue'

const router = useRouter()

const comments = ref([])
const loading = ref(true)
const filters = ref({
  search: ''
})
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0
})

let searchTimeout = null

const fetchComments = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      ...filters.value
    }
    
    Object.keys(params).forEach(key => {
      if (params[key] === '') delete params[key]
    })

    const response = await getAdminComments(params)
    const data = response.data.data
    
    comments.value = data.data
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      per_page: data.per_page,
      total: data.total
    }
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const debouncedSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
  
  searchTimeout = setTimeout(() => {
    fetchComments(1)
  }, 500)
}

const changePage = (page) => {
  fetchComments(page)
}

const viewPost = (postId) => {
  if (postId) {
    router.push(`/posts/${postId}`)
  }
}

const confirmDelete = (comment) => {
  if (confirm('Voulez-vous vraiment supprimer ce commentaire ?')) {
    handleDelete(comment.id)
  }
}

const handleDelete = async (commentId) => {
  try {
    await deleteAdminComment(commentId)
    await fetchComments(pagination.value.current_page)
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

onMounted(() => {
  fetchComments()
})
</script>

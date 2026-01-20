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
          <div class="mb-6 flex items-center justify-between">
            <div>
              <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
                Categories
              </h1>
              <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Gerez les categories d'articles
              </p>
            </div>
            <button
              @click="showCreateModal = true"
              class="px-4 py-2 text-sm font-medium rounded-md text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 transition flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Nouvelle categorie
            </button>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="flex justify-center items-center py-20">
            <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <!-- Liste des categories -->
          <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="category in categories"
              :key="category.id"
              class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4"
            >
              <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                  <div
                    class="h-10 w-10 rounded-md flex items-center justify-center"
                    :style="{ backgroundColor: category.color + '20' }"
                  >
                    <svg class="w-5 h-5" :style="{ color: category.color }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="font-medium text-zinc-900 dark:text-zinc-50 text-sm">
                      {{ category.name }}
                    </h3>
                    <p class="text-xs text-zinc-400">
                      {{ category.posts_count || 0 }} article{{ (category.posts_count || 0) > 1 ? 's' : '' }}
                    </p>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-1">
                  <button
                    @click="editCategory(category)"
                    class="p-1.5 rounded-md text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-300 transition"
                    title="Modifier"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </button>
                  <button
                    @click="confirmDelete(category)"
                    :disabled="category.posts_count > 0"
                    class="p-1.5 rounded-md text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :title="category.posts_count > 0 ? 'Contient des articles' : 'Supprimer'"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Infos -->
              <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                <code class="text-zinc-400 font-mono">{{ category.slug }}</code>
                <div class="flex items-center gap-1.5">
                  <div class="w-3 h-3 rounded" :style="{ backgroundColor: category.color }"></div>
                  <span class="text-zinc-400 font-mono">{{ category.color }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Creer/Modifier -->
    <div
      v-if="showCreateModal || editingCategory"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      @click.self="closeModal"
    >
      <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg max-w-sm w-full p-6">
        <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-50 mb-5">
          {{ editingCategory ? 'Modifier la categorie' : 'Nouvelle categorie' }}
        </h2>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Nom -->
          <div>
            <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
              Nom
            </label>
            <input
              v-model="formData.name"
              type="text"
              required
              class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
              placeholder="Ex: Technologie"
            />
          </div>

          <!-- Couleur -->
          <div>
            <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-1.5">
              Couleur
            </label>
            <div class="flex gap-2">
              <input
                v-model="formData.color"
                type="color"
                class="h-9 w-14 rounded-md border border-zinc-200 dark:border-zinc-700 cursor-pointer"
              />
              <input
                v-model="formData.color"
                type="text"
                pattern="^#[0-9A-Fa-f]{6}$"
                class="flex-1 px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 font-mono focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 transition"
                placeholder="#3B82F6"
              />
            </div>
          </div>

          <!-- Preview -->
          <div class="bg-zinc-50 dark:bg-zinc-800 rounded-md p-3">
            <p class="text-xs text-zinc-400 mb-2">Apercu</p>
            <span
              class="inline-block px-2.5 py-1 rounded text-xs font-medium text-white"
              :style="{ backgroundColor: formData.color }"
            >
              {{ formData.name || 'Categorie' }}
            </span>
          </div>

          <!-- Message d'erreur -->
          <div v-if="errorMessage" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-3">
            <p class="text-xs text-red-600 dark:text-red-400">{{ errorMessage }}</p>
          </div>

          <!-- Boutons -->
          <div class="flex gap-3 pt-2">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-3 py-2 text-sm font-medium border border-zinc-200 dark:border-zinc-700 rounded-md text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="flex-1 px-3 py-2 text-sm font-medium rounded-md text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 disabled:opacity-50 transition"
            >
              {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { getCategories, createCategory, updateAdminCategory, deleteAdminCategory } from '@/services/api'
import AdminSidebar from '@/components/AdminSidebar.vue'

const categories = ref([])
const loading = ref(true)
const saving = ref(false)
const showCreateModal = ref(false)
const editingCategory = ref(null)
const errorMessage = ref('')

const formData = reactive({
  name: '',
  color: '#3B82F6'
})

const fetchCategories = async () => {
  loading.value = true
  try {
    const response = await getCategories()
    categories.value = response.data.data || response.data
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

const editCategory = (category) => {
  editingCategory.value = category
  formData.name = category.name
  formData.color = category.color
}

const closeModal = () => {
  showCreateModal.value = false
  editingCategory.value = null
  formData.name = ''
  formData.color = '#3B82F6'
  errorMessage.value = ''
}

const handleSubmit = async () => {
  saving.value = true
  errorMessage.value = ''
  
  try {
    if (editingCategory.value) {
      await updateAdminCategory(editingCategory.value.id, formData)
    } else {
      await createCategory(formData)
    }
    await fetchCategories()
    closeModal()
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Une erreur est survenue'
  } finally {
    saving.value = false
  }
}

const confirmDelete = async (category) => {
  if (category.posts_count > 0) return
  
  if (confirm(`Supprimer la categorie "${category.name}" ?`)) {
    try {
      await deleteAdminCategory(category.id)
      await fetchCategories()
    } catch (error) {
      console.error('Erreur:', error)
    }
  }
}

onMounted(() => {
  fetchCategories()
})
</script>

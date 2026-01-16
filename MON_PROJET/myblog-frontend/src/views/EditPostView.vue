<template>
  <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">
            Modifier l'article
          </h1>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-20">
          <svg class="animate-spin h-12 w-12 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Formulaire -->
        <form v-else-if="post" @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Message d'erreur -->
          <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <p class="text-sm text-red-700">{{ errorMessage }}</p>
          </div>

          <!-- Titre -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              Titre *
            </label>
            <input
              id="title"
              v-model="title"
              type="text"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
            />
          </div>

          <!-- NOUVEAU : Catégorie -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
              Catégorie
            </label>
            <select
              id="category"
              v-model="categoryId"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
            >
              <option :value="null">Aucune catégorie</option>
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
          </div>

          <!-- Contenu -->
          <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
              Contenu *
            </label>
            <textarea
              id="content"
              v-model="content"
              rows="12"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none"
            ></textarea>
          </div>

          <!-- Boutons -->
          <div class="flex gap-4">
            <button
              type="submit"
              :disabled="saving"
              class="flex-1 bg-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-orange-700 disabled:opacity-50 transition"
            >
              {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
            </button>
            <button
              type="button"
              @click="$router.back()"
              class="px-6 py-3 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition"
            >
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { getPost, updatePost, getCategories } from '@/services/api'

const router = useRouter()
const route = useRoute()

const post = ref(null)
const title = ref('')
const content = ref('')
const categoryId = ref(null) // NOUVEAU
const categories = ref([]) // NOUVEAU
const loading = ref(true)
const saving = ref(false)
const errorMessage = ref('')

// NOUVEAU : Charger les catégories
const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const fetchPost = async () => {
  try {
    const response = await getPost(route.params.id)
    post.value = response.data.data
    title.value = post.value.title
    content.value = post.value.content
    categoryId.value = post.value.category_id // NOUVEAU
  } catch (error) {
    errorMessage.value = 'Article introuvable'
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  errorMessage.value = ''
  saving.value = true

  try {
    // MODIFIÉ : Inclure category_id
    await updatePost(route.params.id, title.value, content.value, categoryId.value)
    router.push(`/posts/${route.params.id}`)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Erreur lors de la mise à jour'
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await fetchCategories() // NOUVEAU
  await fetchPost()
})
</script>
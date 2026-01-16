<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-teal-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">
              Créer un nouvel article
          </h1>
          <p class="mt-2 text-gray-600">
            Partagez vos idées avec la communauté
          </p>
        </div>

        <!-- Messages -->
        <div v-if="errorMessage" class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
          <p class="text-sm text-red-700">{{ errorMessage }}</p>
        </div>

        <div v-if="successMessage" class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
          <p class="text-sm text-green-700">{{ successMessage }}</p>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Titre -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              Titre de l'article *
            </label>
            <input
              id="title"
              v-model="title"
              type="text"
              required
              maxlength="255"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
              placeholder="Un titre accrocheur..."
            />
            <p class="mt-1 text-xs text-gray-500">{{ title.length }}/255 caractères</p>
          </div>

          <!-- NOUVEAU : Catégorie -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
              Catégorie
            </label>
            <select
              id="category"
              v-model="categoryId"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
            >
              <option :value="null">Aucune catégorie</option>
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }} ({{ category.posts_count }} articles)
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
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none"
              placeholder="Écrivez votre article ici..."
            ></textarea>
            <p class="mt-1 text-xs text-gray-500">{{ content.length }} caractères</p>
          </div>

          <!-- Boutons -->
          <div class="flex gap-4">
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition"
            >
              {{ loading ? 'Publication...' : 'Publier' }}
            </button>
            <button
              type="button"
              @click="$router.push('/')"
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
import { useRouter } from 'vue-router'
import { createPost, getCategories } from '@/services/api'

const router = useRouter()

const title = ref('')
const content = ref('')
const categoryId = ref(null) // ← NOUVEAU
const categories = ref([]) // ← NOUVEAU
const errorMessage = ref('')
const successMessage = ref('')
const loading = ref(false)

// NOUVEAU : Charger les catégories
const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Erreur lors du chargement des catégories:', error)
  }
}

const handleSubmit = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  loading.value = true

  try {
    // Modifié pour inclure category_id
    await createPost(title.value, content.value, categoryId.value)
    successMessage.value = 'Article publié avec succès !'
    
    setTimeout(() => {
      router.push('/')
    }, 1500)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Erreur lors de la création de l\'article'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCategories() // ← NOUVEAU
})
</script>
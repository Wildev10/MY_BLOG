<template>
  <div class="min-h-screen bg-gradient-to-br from-green-50 to-teal-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-200">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-transparent dark:border-gray-700">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Créer un nouvel article
          </h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Partagez vos idées avec la communauté
          </p>
        </div>

        <!-- Messages -->
        <div v-if="errorMessage" class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
          <p class="text-sm text-red-700 dark:text-red-400">{{ errorMessage }}</p>
        </div>

        <div v-if="successMessage" class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded">
          <p class="text-sm text-green-700 dark:text-green-400">{{ successMessage }}</p>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Titre -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Titre de l'article *
            </label>
            <input
              id="title"
              v-model="title"
              type="text"
              required
              maxlength="255"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
              placeholder="Un titre accrocheur..."
            />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ title.length }}/255 caractères</p>
          </div>

          <!-- NOUVEAU : Image -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Image (optionnelle)
            </label>
            
            <!-- Preview de l'image -->
            <div v-if="imagePreview" class="mb-4">
              <img :src="imagePreview" alt="Preview" class="w-full h-64 object-cover rounded-lg" />
              <button
                type="button"
                @click="removeImage"
                class="mt-2 text-sm text-red-600 dark:text-red-400 hover:underline"
              >
                Supprimer l'image
              </button>
            </div>

            <!-- Input file -->
            <div v-else class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-green-500 dark:hover:border-green-400 transition">
              <input
                type="file"
                ref="fileInput"
                @change="handleFileChange"
                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="hidden"
              />
              <button
                type="button"
                @click="$refs.fileInput.click()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Choisir une image
              </button>
              <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                JPG, PNG, GIF, WEBP (max. 2MB)
              </p>
            </div>
          </div>

          <!-- Catégorie -->
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Catégorie
            </label>
            <select
              id="category"
              v-model="categoryId"
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Contenu *
            </label>
            <TiptapEditor 
              v-model="content" 
              placeholder="Écrivez votre article ici... Utilisez la barre d'outils pour formater le texte."
            />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
              Utilisez l'éditeur pour formatter votre texte
            </p>
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
              class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
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
import TiptapEditor from '@/components/TiptapEditor.vue'

const router = useRouter()

const title = ref('')
const content = ref('')
const categoryId = ref(null)
const categories = ref([])
const imageFile = ref(null) // NOUVEAU
const imagePreview = ref(null) // NOUVEAU
const errorMessage = ref('')
const successMessage = ref('')
const loading = ref(false)

const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Erreur lors du chargement des catégories:', error)
  }
}

// NOUVEAU : Gérer le changement de fichier
const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Vérifier la taille (max 2MB)
    if (file.size > 2048 * 1024) {
      errorMessage.value = 'L\'image ne doit pas dépasser 2MB'
      return
    }
    
    imageFile.value = file
    
    // Créer une preview
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

// NOUVEAU : Supprimer l'image
const removeImage = () => {
  imageFile.value = null
  imagePreview.value = null
}

const handleSubmit = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  loading.value = true

  try {
    // NOUVEAU : Créer un FormData
    const formData = new FormData()
    formData.append('title', title.value)
    formData.append('content', content.value)
    
    if (categoryId.value) {
      formData.append('category_id', categoryId.value)
    }
    
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }

    await createPost(formData)
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
  fetchCategories()
})
</script>
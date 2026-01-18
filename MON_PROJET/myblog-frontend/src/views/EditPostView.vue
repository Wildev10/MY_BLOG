<template>
  <div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-200">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 border border-transparent dark:border-gray-700">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
            Modifier l'article
          </h1>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-20">
          <svg class="animate-spin h-12 w-12 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Formulaire -->
        <form v-else-if="post" @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Message d'erreur -->
          <div v-if="errorMessage" class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
            <p class="text-sm text-red-700 dark:text-red-400">{{ errorMessage }}</p>
          </div>

          <!-- Titre -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Titre *
            </label>
            <input
              id="title"
              v-model="title"
              type="text"
              required
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
            />
          </div>

          <!-- NOUVEAU : Image -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Image
            </label>

            <!-- Image actuelle -->
            <div v-if="post.image_url && !imagePreview" class="mb-4">
              <img :src="post.image_url" alt="Image actuelle" class="w-full h-64 object-cover rounded-lg" />
              <button
                type="button"
                @click="removeCurrentImage"
                class="mt-2 text-sm text-red-600 dark:text-red-400 hover:underline"
              >
                Supprimer cette image
              </button>
            </div>

            <!-- Nouvelle image (preview) -->
            <div v-else-if="imagePreview" class="mb-4">
              <img :src="imagePreview" alt="Nouvelle image" class="w-full h-64 object-cover rounded-lg" />
              <button
                type="button"
                @click="cancelNewImage"
                class="mt-2 text-sm text-red-600 dark:text-red-400 hover:underline"
              >
                Annuler la nouvelle image
              </button>
            </div>

            <!-- Upload nouvelle image -->
            <div v-else class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-orange-500 dark:hover:border-orange-400 transition">
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
                {{ post.image_url ? 'Changer l\'image' : 'Ajouter une image' }}
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
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
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
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Contenu *
            </label>
            <textarea
              id="content"
              v-model="content"
              rows="12"
              required
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition resize-none bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
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
import { useRouter, useRoute } from 'vue-router'
import { getPost, updatePost, getCategories } from '@/services/api'

const router = useRouter()
const route = useRoute()

const post = ref(null)
const title = ref('')
const content = ref('')
const categoryId = ref(null)
const categories = ref([])
const imageFile = ref(null) // NOUVEAU
const imagePreview = ref(null) // NOUVEAU
const originalImageUrl = ref(null) // Sauvegarder l'URL originale
const removeImage = ref(false) // NOUVEAU : Flag pour supprimer l'image
const loading = ref(true)
const saving = ref(false)
const errorMessage = ref('')

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
    categoryId.value = post.value.category_id
    originalImageUrl.value = post.value.image_url // Sauvegarder l'URL originale
  } catch (error) {
    errorMessage.value = 'Article introuvable'
  } finally {
    loading.value = false
  }
}

// NOUVEAU : Gérer le changement de fichier
const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 2048 * 1024) {
      errorMessage.value = 'L\'image ne doit pas dépasser 2MB'
      return
    }
    
    imageFile.value = file
    removeImage.value = false
    
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

// NOUVEAU : Supprimer l'image actuelle
const removeCurrentImage = () => {
  removeImage.value = true
  post.value.image_url = null
}

// NOUVEAU : Annuler la nouvelle image
const cancelNewImage = () => {
  imageFile.value = null
  imagePreview.value = null
  removeImage.value = false
  // Restaurer l'image originale si elle existait
  if (originalImageUrl.value) {
    post.value.image_url = originalImageUrl.value
  }
}

const handleSubmit = async () => {
  errorMessage.value = ''
  saving.value = true

  try {
    const formData = new FormData()
    formData.append('title', title.value)
    formData.append('content', content.value)
    
    if (categoryId.value) {
      formData.append('category_id', categoryId.value)
    }
    
    // Si nouvelle image
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }
    
    // Si on veut supprimer l'image (sans en ajouter une nouvelle)
    if (removeImage.value && !imageFile.value) {
      formData.append('remove_image', '1')
    }

    await updatePost(route.params.id, formData)
    router.push(`/posts/${route.params.id}`)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Erreur lors de la mise à jour'
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await fetchCategories()
  await fetchPost()
})
</script>
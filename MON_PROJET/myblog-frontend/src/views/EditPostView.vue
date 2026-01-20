<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 py-12 px-4 sm:px-6 transition-colors">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6 sm:p-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">
            Modifier l'article
          </h1>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-20">
          <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Formulaire -->
        <form v-else-if="post" @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Message d'erreur -->
          <div v-if="errorMessage" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
            <p class="text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
          </div>

          <!-- Titre -->
          <div>
            <label for="title" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Titre *
            </label>
            <input
              id="title"
              v-model="title"
              type="text"
              required
              class="w-full px-3 py-2.5 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition text-sm"
            />
          </div>

          <!-- Image -->
          <div>
            <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Image
            </label>

            <!-- Image actuelle -->
            <div v-if="post.image_url && !removeImageFlag && !imagePreview" class="relative">
              <img :src="post.image_url" alt="Image actuelle" class="w-full h-48 object-cover rounded-md" />
              <div class="absolute top-2 right-2 flex gap-2">
                <button
                  type="button"
                  @click="$refs.fileInput.click()"
                  class="p-1.5 bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-zinc-700 transition"
                  title="Changer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </button>
                <button
                  type="button"
                  @click="removeCurrentImage"
                  class="p-1.5 bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-red-500 transition"
                  title="Supprimer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
              <input
                type="file"
                ref="fileInput"
                @change="handleFileChange"
                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="hidden"
              />
            </div>

            <!-- Nouvelle image preview -->
            <div v-else-if="imagePreview" class="relative">
              <img :src="imagePreview" alt="Nouvelle image" class="w-full h-48 object-cover rounded-md" />
              <button
                type="button"
                @click="cancelNewImage"
                class="absolute top-2 right-2 p-1.5 bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:text-red-500 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <!-- Zone d'upload -->
            <div v-else class="border border-dashed border-zinc-300 dark:border-zinc-700 rounded-md p-6 text-center hover:border-zinc-400 dark:hover:border-zinc-600 transition">
              <input
                type="file"
                ref="fileInputNew"
                @change="handleFileChange"
                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="hidden"
              />
              <button
                type="button"
                @click="$refs.fileInputNew.click()"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 rounded-md hover:bg-zinc-200 dark:hover:bg-zinc-700 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Ajouter une image
              </button>
              <p class="mt-2 text-xs text-zinc-400">JPG, PNG, GIF, WEBP (max. 2MB)</p>
              <button
                v-if="removeImageFlag && originalImageUrl"
                type="button"
                @click="cancelNewImage"
                class="mt-3 text-xs text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition"
              >
                Restaurer l'image originale
              </button>
            </div>
          </div>

          <!-- Categorie -->
          <div>
            <label for="category" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Categorie
            </label>
            <select
              id="category"
              v-model="categoryId"
              class="w-full px-3 py-2.5 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition text-sm"
            >
              <option :value="null">Aucune categorie</option>
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
            <label class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Contenu *
            </label>
            <TiptapEditor 
              v-model="content" 
              placeholder="Modifiez le contenu..."
            />
          </div>

          <!-- Boutons -->
          <div class="flex gap-3 pt-4">
            <button
              type="submit"
              :disabled="saving"
              class="flex-1 px-4 py-2.5 text-sm font-medium rounded-md text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 disabled:opacity-50 disabled:cursor-not-allowed transition"
            >
              {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
            </button>
            <button
              type="button"
              @click="$router.back()"
              class="px-4 py-2.5 text-sm font-medium rounded-md text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
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
import TiptapEditor from '@/components/TiptapEditor.vue'

const router = useRouter()
const route = useRoute()

const post = ref(null)
const title = ref('')
const content = ref('')
const categoryId = ref(null)
const categories = ref([])
const imageFile = ref(null)
const imagePreview = ref(null)
const originalImageUrl = ref(null)
const removeImageFlag = ref(false)
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
    originalImageUrl.value = post.value.image_url
  } catch (error) {
    errorMessage.value = 'Article introuvable'
  } finally {
    loading.value = false
  }
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 2048 * 1024) {
      errorMessage.value = "L'image ne doit pas depasser 2MB"
      return
    }
    
    imageFile.value = file
    removeImageFlag.value = false
    
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removeCurrentImage = () => {
  removeImageFlag.value = true
  post.value.image_url = null
}

const cancelNewImage = () => {
  imageFile.value = null
  imagePreview.value = null
  removeImageFlag.value = false
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
    
    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }
    
    if (removeImageFlag.value && !imageFile.value) {
      formData.append('remove_image', '1')
    }

    await updatePost(route.params.id, formData)
    router.push('/posts/' + route.params.id)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Erreur lors de la mise a jour'
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await fetchCategories()
  await fetchPost()
})
</script>

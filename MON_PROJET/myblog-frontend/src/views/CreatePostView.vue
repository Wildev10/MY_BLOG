<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 transition-colors">
    <!-- Background decorations -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-blue-100/20 to-indigo-100/20 dark:from-blue-900/5 dark:to-indigo-900/5 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-gradient-to-tr from-purple-100/20 to-pink-100/20 dark:from-purple-900/5 dark:to-pink-900/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-3xl mx-auto py-12 px-4 sm:px-6">
      <!-- Back button -->
      <button
        @click="$router.push('/')"
        class="group mb-6 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm"
      >
        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
      </button>

      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xl shadow-slate-200/50 dark:shadow-none">
        <!-- Header -->
        <div class="mb-8 text-center sm:text-left">
          <div class="inline-flex items-center gap-3 mb-4">
            <div class="p-3 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 rounded-xl">
              <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                Nouvel article
              </h1>
              <p class="text-sm text-slate-500 dark:text-slate-400">
                Partagez vos idées avec la communauté
              </p>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
        >
          <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
          </div>
        </transition>

        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
        >
          <div v-if="successMessage" class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900/30 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-emerald-600 dark:text-emerald-400">{{ successMessage }}</p>
          </div>
        </transition>

        <!-- Formulaire -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Titre -->
          <div class="space-y-2">
            <label for="title" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
              Titre <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input
                id="title"
                v-model="title"
                type="text"
                required
                maxlength="255"
                class="w-full px-4 py-3.5 pl-12 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-white focus:border-transparent focus:bg-white dark:focus:bg-slate-900 transition-all"
                placeholder="Un titre accrocheur..."
              />
              <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
              </svg>
            </div>
            <div class="flex justify-end">
              <span class="text-xs text-slate-400">{{ title.length }}/255</span>
            </div>
          </div>

          <!-- Image -->
          <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
              Image de couverture
            </label>
            
            <!-- Preview -->
            <div v-if="imagePreview" class="relative group overflow-hidden rounded-xl">
              <img :src="imagePreview" alt="Preview" class="w-full h-56 object-cover" />
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                <button
                  type="button"
                  @click="$refs.fileInput.click()"
                  class="p-3 bg-white/20 hover:bg-white/30 rounded-xl backdrop-blur-sm text-white transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </button>
                <button
                  type="button"
                  @click="removeImageHandler"
                  class="p-3 bg-red-500/80 hover:bg-red-500 rounded-xl text-white transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Upload zone -->
            <div 
              v-else 
              class="relative border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-8 text-center hover:border-slate-400 dark:hover:border-slate-600 transition-colors bg-slate-50/50 dark:bg-slate-800/50 group cursor-pointer"
              @click="$refs.fileInput.click()"
              @dragover.prevent="isDragging = true"
              @dragleave="isDragging = false"
              @drop.prevent="handleDrop"
              :class="{ 'border-slate-400 dark:border-slate-500 bg-slate-100 dark:bg-slate-800': isDragging }"
            >
              <input
                type="file"
                ref="fileInput"
                @change="handleFileChange"
                accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                class="hidden"
              />
              <div class="flex flex-col items-center">
                <div class="w-16 h-16 mb-4 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-1">
                  Glissez une image ou cliquez pour parcourir
                </p>
                <p class="text-xs text-slate-400">JPG, PNG, GIF, WEBP (max. 2MB)</p>
              </div>
            </div>
          </div>

          <!-- Categorie -->
          <div class="space-y-2">
            <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
              Catégorie
            </label>
            <div class="relative">
              <select
                id="category"
                v-model="categoryId"
                class="w-full px-4 py-3.5 pl-12 text-sm border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-white focus:border-transparent focus:bg-white dark:focus:bg-slate-900 transition-all appearance-none cursor-pointer"
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
              <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
              </svg>
              <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </div>
          </div>

          <!-- Contenu -->
          <div class="space-y-2">
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
              Contenu <span class="text-red-500">*</span>
            </label>
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 focus-within:ring-2 focus-within:ring-slate-900 dark:focus-within:ring-white focus-within:border-transparent transition-all">
              <TiptapEditor 
                v-model="content" 
                placeholder="Écrivez votre article ici..."
              />
            </div>
          </div>

          <!-- Boutons -->
          <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button
              type="submit"
              :disabled="loading || !title || !content"
              class="relative flex-1 py-4 px-6 text-base font-semibold rounded-xl text-white bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 dark:focus:ring-white disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-lg shadow-slate-900/20 dark:shadow-none overflow-hidden group"
            >
              <span :class="{ 'opacity-0': loading }" class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Publier l'article
              </span>
              <span v-if="loading" class="absolute inset-0 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              <div class="absolute inset-0 -translate-x-full group-hover:translate-x-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-500"></div>
            </button>
            <button
              type="button"
              @click="$router.push('/')"
              class="px-6 py-4 text-base font-semibold rounded-xl text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
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
const imageFile = ref(null)
const imagePreview = ref(null)
const errorMessage = ref('')
const successMessage = ref('')
const loading = ref(false)
const isDragging = ref(false)

const fetchCategories = async () => {
  try {
    const response = await getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  processFile(file)
}

const handleDrop = (event) => {
  isDragging.value = false
  const file = event.dataTransfer.files[0]
  if (file && file.type.startsWith('image/')) {
    processFile(file)
  }
}

const processFile = (file) => {
  if (!file) return
  
  if (file.size > 2048 * 1024) {
    errorMessage.value = "L'image ne doit pas dépasser 2MB"
    return
  }
  
  imageFile.value = file
  
  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const removeImageHandler = () => {
  imageFile.value = null
  imagePreview.value = null
}

const handleSubmit = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  loading.value = true

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

    await createPost(formData)
    successMessage.value = 'Article publié avec succès !'
    
    setTimeout(() => {
      router.push('/')
    }, 1500)
  } catch (error) {
    errorMessage.value = error.response?.data?.message || "Erreur lors de la création"
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCategories()
})
</script>

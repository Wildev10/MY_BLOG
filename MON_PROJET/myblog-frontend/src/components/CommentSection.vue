<template>
  <div class="border-t border-zinc-200 dark:border-zinc-800 pt-8">
    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50 mb-6">
      Commentaires ({{ comments.length }})
    </h3>

    <!-- Formulaire -->
    <div v-if="authStore.isAuthenticated" class="mb-6">
      <form @submit.prevent="handleSubmit">
        <textarea
          v-model="newComment"
          rows="3"
          placeholder="Ajouter un commentaire..."
          class="w-full px-3 py-2.5 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-400 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition resize-none"
        ></textarea>
        <div class="flex justify-end gap-2 mt-3">
          <button
            v-if="newComment.trim()"
            type="button"
            @click="cancelEdit"
            class="px-3 py-1.5 text-sm text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="!newComment.trim() || submitting"
            class="px-4 py-1.5 text-sm font-medium rounded-md text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 hover:bg-zinc-800 dark:hover:bg-zinc-200 disabled:opacity-50 disabled:cursor-not-allowed transition"
          >
            {{ submitting ? 'Envoi...' : 'Commenter' }}
          </button>
        </div>
      </form>
    </div>

    <div v-else class="mb-6 p-4 bg-zinc-100 dark:bg-zinc-800 rounded-md text-center">
      <p class="text-sm text-zinc-500 dark:text-zinc-400">
        <router-link to="/login" class="text-zinc-900 dark:text-zinc-50 hover:underline font-medium">
          Connectez-vous
        </router-link>
        pour commenter
      </p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-8">
      <svg class="animate-spin h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <!-- Liste -->
    <div v-else-if="comments.length > 0" class="space-y-4">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="p-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-md border border-zinc-100 dark:border-zinc-800"
      >
        <div class="flex items-start gap-3">
          <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
            <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">
              {{ comment.user?.name?.charAt(0) || '?' }}
            </span>
          </div>
          
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-1">
              <div>
                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50">
                  {{ comment.user?.name || 'Anonyme' }}
                </span>
                <span class="text-xs text-zinc-400 ml-2">
                  {{ formatDate(comment.created_at) }}
                </span>
              </div>
              
              <div v-if="authStore.user?.id === comment.user_id" class="flex gap-1">
                <button
                  @click="startEdit(comment)"
                  class="p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition"
                  title="Modifier"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click="confirmDelete(comment.id)"
                  class="p-1 text-zinc-400 hover:text-red-500 transition"
                  title="Supprimer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Mode edition -->
            <div v-if="editingComment?.id === comment.id" class="mt-2">
              <textarea
                v-model="editContent"
                rows="2"
                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition resize-none"
              ></textarea>
              <div class="flex justify-end gap-2 mt-2">
                <button @click="cancelEdit" class="px-2 py-1 text-xs text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300">
                  Annuler
                </button>
                <button @click="handleUpdate" class="px-2 py-1 text-xs font-medium text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 rounded hover:bg-zinc-800 dark:hover:bg-zinc-200">
                  Enregistrer
                </button>
              </div>
            </div>
            
            <!-- Mode lecture -->
            <p v-else class="text-sm text-zinc-600 dark:text-zinc-400 whitespace-pre-wrap">
              {{ comment.content }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-8">
      <p class="text-sm text-zinc-400">Aucun commentaire. Soyez le premier !</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { getComments, createComment, updateComment, deleteComment } from '@/services/api'

const props = defineProps({
  postId: {
    type: [Number, String],
    required: true
  }
})

const authStore = useAuthStore()

const comments = ref([])
const newComment = ref('')
const editingComment = ref(null)
const editContent = ref('')
const loading = ref(false)
const submitting = ref(false)

const fetchComments = async () => {
  loading.value = true
  try {
    const response = await getComments(props.postId)
    comments.value = response.data.data.data || response.data.data
  } catch (error) {
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

watch(() => props.postId, (newPostId, oldPostId) => {
  if (newPostId !== oldPostId) {
    fetchComments()
  }
}, { immediate: false })

const handleSubmit = async () => {
  if (!newComment.value.trim()) return
  
  submitting.value = true
  try {
    const response = await createComment(props.postId, newComment.value)
    comments.value.unshift(response.data.data)
    newComment.value = ''
  } catch (error) {
    console.error('Erreur:', error)
    alert(error.response?.data?.message || "Erreur lors de l'ajout")
  } finally {
    submitting.value = false
  }
}

const startEdit = (comment) => {
  editingComment.value = comment
  editContent.value = comment.content
}

const cancelEdit = () => {
  editingComment.value = null
  editContent.value = ''
  newComment.value = ''
}

const handleUpdate = async () => {
  if (!editContent.value.trim()) return
  
  try {
    const response = await updateComment(editingComment.value.id, editContent.value)
    const index = comments.value.findIndex(c => c.id === editingComment.value.id)
    if (index !== -1) {
      comments.value[index] = response.data.data
    }
    cancelEdit()
  } catch (error) {
    console.error('Erreur:', error)
    alert('Erreur lors de la modification')
  }
}

const confirmDelete = (id) => {
  if (confirm('Supprimer ce commentaire ?')) {
    handleDelete(id)
  }
}

const handleDelete = async (id) => {
  try {
    await deleteComment(id)
    comments.value = comments.value.filter(c => c.id !== id)
  } catch (error) {
    console.error('Erreur:', error)
    alert('Erreur lors de la suppression')
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date
  
  if (diff < 60000) return "A l'instant"
  if (diff < 3600000) {
    const minutes = Math.floor(diff / 60000)
    return "Il y a " + minutes + " min"
  }
  if (diff < 86400000) {
    const hours = Math.floor(diff / 3600000)
    return "Il y a " + hours + "h"
  }
  
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short'
  })
}

onMounted(() => {
  fetchComments()
})
</script>

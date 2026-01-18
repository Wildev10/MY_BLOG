<template>
  <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
      💬 Commentaires ({{ comments.length }})
    </h3>

    <!-- Formulaire d'ajout de commentaire -->
    <div v-if="authStore.isAuthenticated" class="mb-8">
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <textarea
          v-model="newComment"
          rows="3"
          placeholder="Ajouter un commentaire..."
          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
        ></textarea>
        <div class="flex justify-end gap-3">
          <button
            v-if="newComment.trim()"
            type="button"
            @click="cancelEdit"
            class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="!newComment.trim() || submitting"
            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
          >
            {{ submitting ? 'Envoi...' : 'Commenter' }}
          </button>
        </div>
      </form>
    </div>

    <div v-else class="mb-8 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-center">
      <p class="text-gray-600 dark:text-gray-400">
        <router-link to="/login" class="text-indigo-600 dark:text-indigo-400 hover:underline">
          Connectez-vous
        </router-link>
        pour commenter
      </p>
    </div>

    <!-- Liste des commentaires -->
    <div v-if="loading" class="flex justify-center py-8">
      <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </div>

    <div v-else-if="comments.length > 0" class="space-y-4">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600"
      >
        <div class="flex items-start gap-3">
          <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
              {{ comment.user?.name?.charAt(0) || '?' }}
            </span>
          </div>
          
          <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
              <div>
                <p class="font-semibold text-gray-900 dark:text-white">
                  {{ comment.user?.name || 'Anonyme' }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ formatDate(comment.created_at) }}
                </p>
              </div>
              
              <div v-if="authStore.user?.id === comment.user_id" class="flex gap-2">
                <button
                  @click="startEdit(comment)"
                  class="text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition"
                  title="Modifier"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </button>
                <button
                  @click="confirmDelete(comment.id)"
                  class="text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition"
                  title="Supprimer"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Mode édition -->
            <div v-if="editingComment?.id === comment.id" class="mt-2">
              <textarea
                v-model="editContent"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
              ></textarea>
              <div class="flex justify-end gap-2 mt-2">
                <button
                  @click="cancelEdit"
                  class="px-3 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
                >
                  Annuler
                </button>
                <button
                  @click="handleUpdate"
                  class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700"
                >
                  Enregistrer
                </button>
              </div>
            </div>
            
            <!-- Mode lecture -->
            <p v-else class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
              {{ comment.content }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
      Aucun commentaire pour le moment. Soyez le premier à commenter !
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { getComments, createComment, updateComment, deleteComment } from '@/services/api'

const props = defineProps({
  postId: {
    type: Number,
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

const handleSubmit = async () => {
  if (!newComment.value.trim()) return
  
  submitting.value = true
  try {
    const response = await createComment(props.postId, newComment.value)
    comments.value.unshift(response.data.data)
    newComment.value = ''
  } catch (error) {
    console.error('Erreur:', error)
    alert('Erreur lors de l\'ajout du commentaire')
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
  
  // Moins d'une minute
  if (diff < 60000) return 'À l\'instant'
  
  // Moins d'une heure
  if (diff < 3600000) {
    const minutes = Math.floor(diff / 60000)
    return `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`
  }
  
  // Moins d'un jour
  if (diff < 86400000) {
    const hours = Math.floor(diff / 3600000)
    return `Il y a ${hours} heure${hours > 1 ? 's' : ''}`
  }
  
  // Sinon date complète
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  fetchComments()
})
</script>
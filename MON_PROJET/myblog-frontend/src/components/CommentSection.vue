<template>
  <div class="space-y-8">
    <div class="flex items-center justify-between">
       <h3 class="text-xl font-bold text-slate-900 dark:text-white">
        Commentaires <span class="text-slate-400 font-normal text-lg ml-1">({{ comments.length }})</span>
        </h3>
    </div>

    <!-- Formulaire (Top) -->
    <div v-if="authStore.isAuthenticated" class="relative group">
      <div v-if="!authStore.user?.avatar_url" class="absolute left-0 top-0 mt-3 ml-3 sm:ml-4 h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center pointer-events-none z-10">
           <span class="text-xs font-bold text-slate-500">{{ authStore.user?.name?.charAt(0) }}</span>
      </div>
      <img v-else :src="authStore.user?.avatar_url" class="absolute left-0 top-0 mt-3 ml-3 sm:ml-4 h-8 w-8 rounded-full object-cover pointer-events-none z-10" />

      <form @submit.prevent="handleSubmit">
        <textarea
          v-model="newComment"
          rows="3"
          placeholder="Ajouter un commentaire..."
          class="w-full pl-14 sm:pl-16 pr-4 py-4 border-2 border-slate-100 dark:border-slate-800 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-black focus:ring-0 focus:border-slate-300 dark:focus:border-slate-700 transition-all resize-none shadow-sm"
        ></textarea>
        
        <div class="flex justify-end gap-3 mt-3">
          <button
            v-if="newComment.trim()"
            type="button"
            @click="cancelEdit"
            class="px-4 py-2 text-sm font-medium text-slate-500 hover:text-slate-800 dark:hover:text-slate-300 transition-colors"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="!newComment.trim() || submitting"
            class="px-6 py-2 text-sm font-semibold rounded-lg text-white bg-slate-900 dark:bg-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-200 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
          >
            {{ submitting ? 'Envoi...' : 'Publier' }}
          </button>
        </div>
      </form>
    </div>

    <div v-else class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-xl text-center border border-dashed border-slate-200 dark:border-slate-700">
      <p class="text-slate-500 dark:text-slate-400">
        <router-link to="/login" class="text-slate-900 dark:text-white hover:underline font-semibold">
          Connectez-vous
        </router-link>
        pour rejoindre la discussion
      </p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-10">
       <div class="w-8 h-8 rounded-full border-2 border-slate-200 border-t-slate-800 animate-spin"></div>
    </div>

    <!-- Liste -->
    <div v-else-if="comments.length > 0" class="space-y-8">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="group flex gap-4 transition-opacity"
        :class="{'opacity-50': submitting && editingComment?.id === comment.id}"
      >
        <div class="flex-shrink-0">
             <div class="h-10 w-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden ring-2 ring-transparent group-hover:ring-slate-100 dark:group-hover:ring-slate-800 transition-all">
                <img v-if="comment.user?.avatar_url" :src="comment.user?.avatar_url" class="h-full w-full object-cover" />
                <span v-else class="text-xs font-bold text-slate-500 dark:text-slate-400">
                    {{ comment.user?.name?.charAt(0) || '?' }}
                </span>
            </div>
        </div>
        
        <div class="flex-1 min-w-0 pt-1">
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
              <span class="text-sm font-bold text-slate-900 dark:text-white">
                {{ comment.user?.name || 'Anonyme' }}
              </span>
              <span class="text-xs text-slate-400">
                {{ formatDate(comment.created_at) }}
              </span>
            </div>
            
            <div v-if="authStore.user?.id === comment.user_id" class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button
                @click="startEdit(comment)"
                class="p-1.5 text-slate-400 hover:text-slate-900 dark:hover:text-white transition rounded hover:bg-slate-100 dark:hover:bg-slate-800"
                title="Modifier"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </button>
              <button
                @click="confirmDelete(comment.id)"
                class="p-1.5 text-slate-400 hover:text-red-600 transition rounded hover:bg-red-50 dark:hover:bg-red-900/20"
                title="Supprimer"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
          
          <!-- Mode edition -->
          <div v-if="editingComment?.id === comment.id" class="relative">
             <textarea
              v-model="editContent"
              rows="2"
              class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-slate-900 dark:focus:ring-white focus:border-transparent transition resize-none"
            ></textarea>
            <div class="flex justify-end gap-2 mt-2">
              <button @click="cancelEdit" class="px-3 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-800">
                Annuler
              </button>
              <button @click="handleUpdate" class="px-3 py-1.5 text-xs font-bold text-white bg-slate-900 dark:bg-white dark:text-slate-900 rounded-md hover:opacity-90">
                Sauvegarder
              </button>
            </div>
          </div>
          
          <!-- Mode lecture -->
          <p v-else class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
            {{ comment.content }}
          </p>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
       <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 dark:bg-slate-800 mb-4">
            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
       </div>
      <p class="text-slate-500 font-medium">Aucun commentaire pour l'instant.</p>
      <p class="text-sm text-slate-400">Lancez la discussion !</p>
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

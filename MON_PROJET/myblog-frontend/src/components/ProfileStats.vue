<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
      Statistiques
    </h3>

    <div class="grid grid-cols-3 gap-4">
      <!-- Nombre d'articles -->
      <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
        <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
          {{ postsCount }}
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          {{ postsCount === 1 ? 'Article' : 'Articles' }}
        </div>
      </div>

      <!-- Nombre de commentaires -->
      <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
        <div class="text-3xl font-bold text-green-600 dark:text-green-400">
          {{ commentsCount }}
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          {{ commentsCount === 1 ? 'Commentaire' : 'Commentaires' }}
        </div>
      </div>

      <!-- Membre depuis -->
      <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
          {{ memberSince }}
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
          Membre depuis
        </div>
      </div>
    </div>

    <!-- Dernier article -->
    <div v-if="lastPostDate" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
      <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Dernier article : {{ formatDate(lastPostDate) }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
})

// Extraire les valeurs de l'utilisateur
const postsCount = computed(() => props.user?.posts_count || 0)
const commentsCount = computed(() => props.user?.comments_count || 0)
const lastPostDate = computed(() => props.user?.last_post_date || null)

// Calculer "membre depuis" en mois/annees
const memberSince = computed(() => {
  if (!props.user?.created_at) return 'N/A'
  
  const created = new Date(props.user.created_at)
  if (isNaN(created.getTime())) return 'N/A'
  
  const now = new Date()
  const diffMs = now - created
  const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))
  
  if (diffDays < 1) {
    return "Aujourd'hui"
  } else if (diffDays < 30) {
    return `${diffDays}j`
  } else if (diffDays < 365) {
    const months = Math.floor(diffDays / 30)
    return `${months} mois`
  } else {
    const years = Math.floor(diffDays / 365)
    return `${years} an${years > 1 ? 's' : ''}`
  }
})

// Formater une date
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}
</script>

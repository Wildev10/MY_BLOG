<template>
  <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-5">
    <h3 class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-4">
      Statistiques
    </h3>

    <div class="grid grid-cols-3 gap-3">
      <!-- Nombre d'articles -->
      <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-md border border-zinc-100 dark:border-zinc-700">
        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
          {{ postsCount }}
        </div>
        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
          {{ postsCount === 1 ? 'Article' : 'Articles' }}
        </div>
      </div>

      <!-- Nombre de commentaires -->
      <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-md border border-zinc-100 dark:border-zinc-700">
        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
          {{ commentsCount }}
        </div>
        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
          {{ commentsCount === 1 ? 'Commentaire' : 'Commentaires' }}
        </div>
      </div>

      <!-- Membre depuis -->
      <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-md border border-zinc-100 dark:border-zinc-700">
        <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
          {{ memberSince }}
        </div>
        <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
          Ancienneté
        </div>
      </div>
    </div>

    <!-- Dernier article -->
    <div v-if="lastPostDate" class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
      <div class="flex items-center text-xs text-zinc-500 dark:text-zinc-400">
        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

const postsCount = computed(() => props.user?.posts_count || 0)
const commentsCount = computed(() => props.user?.comments_count || 0)
const lastPostDate = computed(() => props.user?.last_post_date || null)

const memberSince = computed(() => {
  if (!props.user?.created_at) return 'N/A'
  
  const created = new Date(props.user.created_at)
  if (isNaN(created.getTime())) return 'N/A'
  
  const now = new Date()
  const diffMs = now - created
  const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24))
  
  if (diffDays < 1) {
    return "Auj."
  } else if (diffDays < 30) {
    return diffDays + "j"
  } else if (diffDays < 365) {
    const months = Math.floor(diffDays / 30)
    return months + " mois"
  } else {
    const years = Math.floor(diffDays / 365)
    return years + " an" + (years > 1 ? 's' : '')
  }
})

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

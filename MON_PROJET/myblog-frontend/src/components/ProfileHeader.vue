<template>
  <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden">
    <div class="px-6 py-8">
      <div class="flex flex-col sm:flex-row items-center gap-6">
        <!-- Avatar -->
        <AvatarUpload
          :avatar-url="user.avatar_url"
          :name="user.name"
          :size="editable ? 'xl' : 'lg'"
          :editable="editable"
          :has-custom-avatar="!!user.avatar"
          @upload="$emit('avatar-upload', $event)"
          @delete="$emit('avatar-delete')"
        />

        <!-- Infos -->
        <div class="text-center sm:text-left flex-1">
          <h1 class="text-xl sm:text-2xl font-semibold text-zinc-900 dark:text-zinc-50">
            {{ user.name }}
          </h1>
          
          <p v-if="user.username" class="text-zinc-500 dark:text-zinc-400 mt-0.5">
            @{{ user.username }}
          </p>

          <p v-if="user.bio" class="text-zinc-600 dark:text-zinc-300 mt-3 max-w-lg text-sm">
            {{ user.bio }}
          </p>

          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 mt-4 text-xs text-zinc-500 dark:text-zinc-400">
            <span v-if="user.location" class="flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              {{ user.location }}
            </span>

            <span class="flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              Membre depuis {{ formatDate(user.created_at) }}
            </span>
          </div>
        </div>

        <!-- Actions -->
        <div v-if="editable" class="flex flex-col gap-2">
          <router-link
            to="/profile/edit"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-md transition"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Modifier
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AvatarUpload from './AvatarUpload.vue'

defineProps({
  user: {
    type: Object,
    required: true
  },
  editable: {
    type: Boolean,
    default: false
  }
})

defineEmits(['avatar-upload', 'avatar-delete'])

const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    month: 'long',
    year: 'numeric'
  })
}
</script>

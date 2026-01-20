<template>
  <div class="relative" ref="dropdownRef">
    <!-- Bouton Notifications -->
    <button
      @click.stop="toggleDropdown"
      class="relative p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition"
      title="Notifications"
    >
      <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      
      <!-- Badge du nombre de notifications non lues -->
      <span 
        v-if="notificationStore.unreadCount > 0" 
        class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs font-medium rounded-full h-4 w-4 flex items-center justify-center"
      >
        {{ notificationStore.unreadCount > 9 ? '9+' : notificationStore.unreadCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div
      v-if="isOpen"
      @click.stop
      class="absolute right-0 mt-2 w-80 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-lg z-50"
    >
      <!-- Header -->
      <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
          Notifications
        </h3>
        <button
          v-if="notificationStore.notifications.length > 0"
          @click.stop="markAllRead"
          class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition"
        >
          Tout marquer comme lu
        </button>
      </div>

      <!-- Liste des notifications -->
      <div class="max-h-80 overflow-y-auto">
        <!-- Loading -->
        <div v-if="notificationStore.loading" class="p-6 text-center">
          <svg class="animate-spin h-6 w-6 text-zinc-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Notifications -->
        <div v-else-if="notificationStore.notifications.length > 0">
          <div
            v-for="notification in notificationStore.notifications"
            :key="notification.id"
            @click="handleNotificationClick(notification)"
            :class="[
              'px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer transition border-b border-zinc-50 dark:border-zinc-800 last:border-0',
              !notification.is_read && 'bg-zinc-50 dark:bg-zinc-800/50'
            ]"
          >
            <div class="flex items-start gap-3">
              <!-- Avatar -->
              <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
                <span class="text-zinc-600 dark:text-zinc-300 text-sm font-medium">
                  {{ notification.from_user?.name?.charAt(0) || '?' }}
                </span>
              </div>

              <div class="flex-1 min-w-0">
                <!-- Type de notification -->
                <p class="text-sm text-zinc-700 dark:text-zinc-300">
                  <span class="font-medium text-zinc-900 dark:text-zinc-50">{{ notification.from_user?.name }}</span>
                  <span v-if="notification.type === 'like'"> a aimé</span>
                  <span v-else-if="notification.type === 'comment'"> a commenté</span>
                  <span class="font-medium"> "{{ truncate(notification.post?.title, 25) }}"</span>
                </p>

                <!-- Date -->
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">
                  {{ formatDate(notification.created_at) }}
                </p>
              </div>

              <!-- Badge non lu -->
              <div v-if="!notification.is_read" class="flex-shrink-0 mt-2">
                <span class="h-1.5 w-1.5 bg-zinc-900 dark:bg-zinc-50 rounded-full block"></span>
              </div>

              <!-- Bouton supprimer -->
              <button
                @click.stop="removeNotification(notification.id)"
                class="flex-shrink-0 text-zinc-300 dark:text-zinc-600 hover:text-red-500 dark:hover:text-red-400 transition"
                title="Supprimer"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Aucune notification -->
        <div v-else class="p-6 text-center">
          <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
          </svg>
          <p class="text-sm text-zinc-400 dark:text-zinc-500">Aucune notification</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotificationStore } from '@/stores/notification'

const router = useRouter()
const notificationStore = useNotificationStore()
const isOpen = ref(false)
const dropdownRef = ref(null)

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false
  }
}

const toggleDropdown = async () => {
  isOpen.value = !isOpen.value
  
  if (isOpen.value) {
    await notificationStore.fetchNotifications()
  }
}

const handleNotificationClick = async (notification) => {
  if (!notification.is_read) {
    await notificationStore.markNotificationAsRead(notification.id)
  }
  isOpen.value = false
  router.push('/posts/' + notification.post_id)
}

const markAllRead = async () => {
  await notificationStore.markAllNotificationsAsRead()
}

const removeNotification = async (notificationId) => {
  await notificationStore.removeNotification(notificationId)
}

const truncate = (text, length) => {
  if (!text) return ''
  return text.length > length ? text.substring(0, length) + '...' : text
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = now - date

  if (diff < 60000) return "À l'instant"
  if (diff < 3600000) {
    const minutes = Math.floor(diff / 60000)
    return 'Il y a ' + minutes + ' min'
  }
  if (diff < 86400000) {
    const hours = Math.floor(diff / 3600000)
    return 'Il y a ' + hours + 'h'
  }
  if (diff < 604800000) {
    const days = Math.floor(diff / 86400000)
    return 'Il y a ' + days + 'j'
  }
  
  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short'
  })
}

onMounted(() => {
  notificationStore.fetchUnreadCount()
  document.addEventListener('click', handleClickOutside)
  
  setInterval(() => {
    notificationStore.fetchUnreadCount()
  }, 30000)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
.max-h-80::-webkit-scrollbar {
  width: 4px;
}

.max-h-80::-webkit-scrollbar-track {
  background: transparent;
}

.max-h-80::-webkit-scrollbar-thumb {
  background: #d4d4d8;
  border-radius: 2px;
}

.dark .max-h-80::-webkit-scrollbar-thumb {
  background: #3f3f46;
}
</style>

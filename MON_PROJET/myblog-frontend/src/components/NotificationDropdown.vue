<template>
  <div class="relative" ref="dropdownRef">
    <!-- Bouton Notifications -->
    <button
      @click.stop="toggleDropdown"
      class="relative p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
      title="Notifications"
    >
      <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      
      <!-- Badge du nombre de notifications non lues -->
      <span 
        v-if="notificationStore.unreadCount > 0" 
        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
      >
        {{ notificationStore.unreadCount > 9 ? '9+' : notificationStore.unreadCount }}
      </span>
    </button>

    <!-- Dropdown -->
    <div
      v-if="isOpen"
      @click.stop
      class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
    >
      <!-- Header -->
      <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
          Notifications
        </h3>
        <button
          v-if="notificationStore.notifications.length > 0"
          @click.stop="markAllRead"
          class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
        >
          Tout marquer comme lu
        </button>
      </div>

      <!-- Liste des notifications -->
      <div class="max-h-96 overflow-y-auto">
        <!-- Loading -->
        <div v-if="notificationStore.loading" class="p-8 text-center">
          <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
              'px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition border-b border-gray-100 dark:border-gray-700',
              !notification.is_read && 'bg-indigo-50 dark:bg-indigo-900/20'
            ]"
          >
            <div class="flex items-start gap-3">
              <!-- Avatar -->
              <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                  {{ notification.from_user?.name?.charAt(0) || '?' }}
                </span>
              </div>

              <div class="flex-1 min-w-0">
                <!-- Type de notification -->
                <p class="text-sm text-gray-900 dark:text-white">
                  <span class="font-semibold">{{ notification.from_user?.name }}</span>
                  <span v-if="notification.type === 'like'"> a aimé votre article</span>
                  <span v-else-if="notification.type === 'comment'"> a commenté votre article</span>
                  <span class="font-medium"> "{{ truncate(notification.post?.title, 30) }}"</span>
                </p>

                <!-- Date -->
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  {{ formatDate(notification.created_at) }}
                </p>
              </div>

              <!-- Badge non lu -->
              <div v-if="!notification.is_read" class="flex-shrink-0">
                <span class="h-2 w-2 bg-indigo-600 rounded-full block"></span>
              </div>

              <!-- Bouton supprimer -->
              <button
                @click.stop="removeNotification(notification.id)"
                class="flex-shrink-0 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition"
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
        <div v-else class="p-8 text-center">
          <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
          </svg>
          <p class="text-gray-500 dark:text-gray-400">Aucune notification</p>
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

// Gérer le clic à l'extérieur
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
  router.push(`/posts/${notification.post_id}`)
}

const markAllRead = async () => {
  console.log('Bouton Tout marquer comme lu cliqué')
  await notificationStore.markAllNotificationsAsRead()
  console.log('markAllNotificationsAsRead terminé')
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
    return `Il y a ${minutes} min`
  }
  if (diff < 86400000) {
    const hours = Math.floor(diff / 3600000)
    return `Il y a ${hours}h`
  }
  if (diff < 604800000) {
    const days = Math.floor(diff / 86400000)
    return `Il y a ${days}j`
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
.max-h-96::-webkit-scrollbar {
  width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.max-h-96::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
  background: #a1a1a1;
}
</style>

import { defineStore } from 'pinia'
import { ref } from 'vue'
import { getNotifications, getUnreadCount, markAsRead, markAllAsRead, deleteNotification } from '@/services/api'

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref([])
  const unreadCount = ref(0)
  const loading = ref(false)

  // Charger les notifications
  const fetchNotifications = async () => {
    loading.value = true
    try {
      const response = await getNotifications()
      console.log('Réponse API notifications:', response.data)
      
      // Vérifier la structure des données
      if (response.data.success && response.data.data) {
        // Si c'est un objet paginé, prendre le tableau data
        notifications.value = response.data.data.data || response.data.data
        console.log('Notifications chargées:', notifications.value.length)
      } else {
        console.error('Structure de réponse inattendue:', response.data)
        notifications.value = []
      }
    } catch (error) {
      console.error('Erreur lors du chargement des notifications:', error)
      if (error.response) {
        console.error('Erreur serveur:', error.response.data)
      }
      notifications.value = []
    } finally {
      loading.value = false
    }
  }

  // Charger le nombre de notifications non lues
  const fetchUnreadCount = async () => {
    try {
      const response = await getUnreadCount()
      unreadCount.value = response.data.count
    } catch (error) {
      console.error('Erreur:', error)
    }
  }

  // Marquer comme lue
  const markNotificationAsRead = async (notificationId) => {
    try {
      await markAsRead(notificationId)
      
      // Mettre à jour localement avec réactivité
      notifications.value = notifications.value.map(n => 
        n.id === notificationId ? { ...n, is_read: true } : n
      )
      
      // Décrémenter le compteur
      if (unreadCount.value > 0) {
        unreadCount.value--
      }
    } catch (error) {
      console.error('Erreur:', error)
    }
  }

  // Marquer toutes comme lues
  const markAllNotificationsAsRead = async () => {
    try {
      console.log('Appel markAllAsRead...')
      const response = await markAllAsRead()
      console.log('Réponse markAllAsRead:', response.data)
      
      // Mettre à jour localement avec réactivité
      notifications.value = notifications.value.map(n => ({ ...n, is_read: true }))
      console.log('Notifications après mise à jour:', notifications.value)
      
      unreadCount.value = 0
    } catch (error) {
      console.error('Erreur markAllAsRead:', error)
      if (error.response) {
        console.error('Détails erreur:', error.response.data)
      }
    }
  }

  // Supprimer une notification
  const removeNotification = async (notificationId) => {
    try {
      await deleteNotification(notificationId)
      
      // Retirer localement
      const index = notifications.value.findIndex(n => n.id === notificationId)
      if (index !== -1) {
        const wasUnread = !notifications.value[index].is_read
        notifications.value.splice(index, 1)
        
        if (wasUnread && unreadCount.value > 0) {
          unreadCount.value--
        }
      }
    } catch (error) {
      console.error('Erreur:', error)
    }
  }

  return {
    notifications,
    unreadCount,
    loading,
    fetchNotifications,
    fetchUnreadCount,
    markNotificationAsRead,
    markAllNotificationsAsRead,
    removeNotification
  }
})
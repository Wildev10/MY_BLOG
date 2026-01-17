import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { login as apiLogin, logout as apiLogout, register as apiRegister, getCurrentUser } from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  // État (données)
  const token = ref(localStorage.getItem('token') || null)
  const user = ref(null)

  // Computed (valeurs calculées)
  const isAuthenticated = computed(() => !!token.value)

  // Actions (fonctions)
  
  // Se connecter
  const login = async (email, password) => {
    try {
      const response = await apiLogin(email, password)
      token.value = response.data.data.token
      user.value = response.data.data.user
      localStorage.setItem('token', token.value)
      return { success: true }
    } catch (error) {
      let errorMessage = 'Erreur de connexion'
      if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      } else if (error.response?.data?.errors) {
        // Gestion des ValidationException de Laravel
        const errors = error.response.data.errors
        errorMessage = Object.values(errors).flat().join(' ')
      }
      return { 
        success: false, 
        message: errorMessage
      }
    }
  }

  // S'inscrire (sans connexion automatique)
  const register = async (name, email, password, password_confirmation) => {
    try {
      await apiRegister(name, email, password, password_confirmation)
      // Ne pas stocker le token - l'utilisateur devra se connecter manuellement
      return { success: true }
    } catch (error) {
      let errorMessage = 'Erreur d\'inscription'
      if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      } else if (error.response?.data?.errors) {
        // Gestion des ValidationException de Laravel
        const errors = error.response.data.errors
        errorMessage = Object.values(errors).flat().join(' ')
      }
      return { 
        success: false, 
        message: errorMessage
      }
    }
  }

  // Se déconnecter
  const logout = async () => {
    try {
      await apiLogout()
    } catch (error) {
      console.error('Erreur lors de la déconnexion', error)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('token')
    }
  }

  // Récupérer l'utilisateur actuel
  const fetchUser = async () => {
    if (!token.value) return
    
    try {
      const response = await getCurrentUser()
      user.value = response.data.data
    } catch (error) {
      console.error('Erreur lors de la récupération de l\'utilisateur', error)
      // Si le token est invalide (401), on déconnecte
      if (error.response && error.response.status === 401) {
        await logout()
      }
    }
  }

  // Initialiser le store (charger l'utilisateur si token existe)
  if (token.value) {
    fetchUser()
  }

  return {
    token,
    user,
    isAuthenticated,
    login,
    register,
    logout,
    fetchUser
  }
})
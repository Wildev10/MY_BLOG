<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 flex items-center justify-center">
    <div class="text-center">
      <div v-if="loading" class="space-y-4">
        <div class="w-16 h-16 mx-auto border-4 border-slate-200 dark:border-slate-700 border-t-slate-900 dark:border-t-white rounded-full animate-spin"></div>
        <p class="text-slate-600 dark:text-slate-400">Connexion en cours...</p>
      </div>
      
      <div v-if="error" class="space-y-4">
        <div class="w-16 h-16 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
          <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </div>
        <p class="text-red-600 dark:text-red-400">{{ error }}</p>
        <router-link to="/login" class="inline-block px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-medium hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">
          Retour à la connexion
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const loading = ref(true)
const error = ref('')

onMounted(async () => {
  const token = route.query.token
  const errorParam = route.query.error
  
  if (errorParam) {
    loading.value = false
    error.value = errorParam
    return
  }
  
  if (token) {
    try {
      // Sauvegarder le token
      await authStore.setTokenFromGoogle(token)
      
      // Récupérer les informations utilisateur
      await authStore.fetchUser()
      
      // Rediriger vers la page d'accueil
      router.push('/')
    } catch (err) {
      loading.value = false
      error.value = "Erreur lors de la connexion. Veuillez réessayer."
    }
  } else {
    loading.value = false
    error.value = "Token manquant. Veuillez réessayer."
  }
})
</script>

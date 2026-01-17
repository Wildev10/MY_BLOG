<template>
  <div class="flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 flex-grow">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-2xl shadow-2xl border border-gray-100 relative overflow-hidden">
      <!-- Decoration -->
      <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-purple-500 to-pink-600"></div>

      <!-- Header -->
      <div class="text-center">
        <div class="mx-auto h-16 w-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center mb-6 shadow-inner">
             <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
             </svg>
          </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">
            Inscription
        </h2>
        <p class="text-sm text-gray-500">
            Commencez à partager vos idées dès aujourd'hui
        </p>
      </div>

      <!-- Message d'erreur -->
      <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0">
        <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md shadow-sm">
          <p class="text-sm text-red-700 font-medium">{{ errorMessage }}</p>
        </div>
      </transition>

      <!-- Formulaire -->
      <form @submit.prevent="handleRegister" class="mt-8 space-y-6">
        <div class="space-y-5">
            <!-- Nom -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Nom complet
              </label>
              <div class="relative">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                 </div>
                 <input
                    id="name"
                    v-model="name"
                    type="text"
                    required
                    class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-150 sm:text-sm bg-gray-50 focus:bg-white"
                    placeholder="John Doe"
                />
              </div>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email
              </label>
              <div class="relative">
                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                 </div>
                 <input
                    id="email"
                    v-model="email"
                    type="email"
                    required
                    class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-150 sm:text-sm bg-gray-50 focus:bg-white"
                    placeholder="votre@email.com"
                />
              </div>
            </div>

            <!-- Password -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Mot de passe
              </label>
              <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <input
                    id="password"
                    v-model="password"
                    type="password"
                    required
                    minlength="8"
                    class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-150 sm:text-sm bg-gray-50 focus:bg-white"
                    placeholder="••••••••"
                />
              </div>
              <p class="mt-1 text-xs text-gray-500 ml-1">Au moins 8 caractères</p>
            </div>

            <!-- Password Confirmation -->
            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirmer le mot de passe
              </label>
              <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                   <input
                    id="password_confirmation"
                    v-model="passwordConfirmation"
                    type="password"
                    required
                    class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-150 sm:text-sm bg-gray-50 focus:bg-white"
                    placeholder="••••••••"
                />
               </div>
            </div>
        </div>

        <!-- Bouton -->
        <button
            type="submit"
            :disabled="loading"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
        >
            <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg class="animate-spin h-5 w-5 text-purple-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Inscription en cours...' : "S'inscrire" }}
        </button>

        <!-- Lien connexion -->
        <div class="text-center pt-2">
            <p class="text-sm text-gray-600">
              Déjà un compte ?
              <router-link to="/login" class="font-bold text-purple-600 hover:text-purple-500 transition duration-150 hover:underline">
                Se connecter
              </router-link>
            </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const loading = ref(false)
const errorMessage = ref('')

const handleRegister = async () => {
    if (password.value !== passwordConfirmation.value) {
        errorMessage.value = "Les mots de passe ne correspondent pas"
        return
    }

  loading.value = true
  errorMessage.value = ''

  try {
    const result = await authStore.register(
      name.value,
      email.value,
      password.value,
      passwordConfirmation.value
    )
    
    if (result.success) {
      // L'inscription connecte automatiquement, rediriger vers l'accueil
      router.push('/')
    } else {
      errorMessage.value = result.message
    }
  } catch (error) {
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = "Une erreur est survenue lors de l'inscription."
    }
  } finally {
    loading.value = false
  }
}
</script>

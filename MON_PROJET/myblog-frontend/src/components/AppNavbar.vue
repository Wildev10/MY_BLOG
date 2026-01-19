<template>
  <nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2">
          <span class="text-2xl font-bold text-indigo-600 hover:text-indigo-700 transition">
            MyBlog
          </span>
        </router-link>

        <!-- Menu Desktop -->
        <div class="hidden md:flex items-center gap-6">
          <router-link
            to="/"
            class="text-gray-700 hover:text-indigo-600 font-medium transition"
          >
            Accueil
          </router-link>

          <template v-if="authStore.isAuthenticated">
            <router-link
              to="/create"
              class="text-gray-700 hover:text-indigo-600 font-medium transition flex items-center gap-1"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Créer
            </router-link>
            <router-link
              to="/my-posts"
              class="text-gray-700 hover:text-indigo-600 font-medium transition"
            >
              Mes articles
            </router-link>

             <!-- AJOUTEZ ICI LE COMPOSANT NOTIFICATIONS -->
            <NotificationDropdown />
            
            
             <!-- NOUVEAU : Bouton Dark Mode -->
            <button
              @click="themeStore.toggleTheme()"
              class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
              title="Basculer le thème"
            >
              <svg v-if="themeStore.isDark" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
              </svg>
              <svg v-else class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
              </svg>
            </button>

            <!-- User menu -->
            <div class="relative group">
              <button class="flex items-center gap-2 hover:bg-gray-100 rounded-lg p-2 transition">
                <div class="h-8 w-8 rounded-full overflow-hidden bg-indigo-100 flex items-center justify-center">
                  <img 
                    v-if="authStore.user?.avatar_url" 
                    :src="authStore.user.avatar_url" 
                    :alt="authStore.user?.name"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="text-indigo-600 font-semibold text-sm">
                    {{ authStore.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <!-- Dropdown menu -->
              <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <router-link
                  to="/profile"
                  class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Mon profil
                </router-link>
                <router-link
                  to="/profile/edit"
                  class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Parametres
                </router-link>
                <hr class="my-1"/>
                <button
                  @click="handleLogout"
                  class="flex items-center gap-2 w-full px-4 py-2 text-red-600 hover:bg-red-50 transition"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Deconnexion
                </button>
              </div>
            </div>
          </template>

          <template v-else>
            <router-link
              to="/login"
              class="text-gray-700 hover:text-indigo-600 font-medium transition"
            >
              Connexion
            </router-link>
            <router-link
              to="/register"
              class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium"
            >
              Inscription
            </router-link>
          </template>
        </div>

        <!-- Menu Mobile (hamburger) -->
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition"
        >
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Menu Mobile déroulant -->
      <div v-if="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-200">
        <div class="flex flex-col gap-3">
          <router-link
            to="/"
            @click="mobileMenuOpen = false"
            class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
          >
            Accueil
          </router-link>

          <template v-if="authStore.isAuthenticated">
            <router-link
              to="/create"
              @click="mobileMenuOpen = false"
              class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
            >
              Creer un article
            </router-link>
            <router-link
              to="/my-posts"
              @click="mobileMenuOpen = false"
              class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
            >
              Mes articles
            </router-link>

             <!-- AJOUTEZ CETTE LIGNE pour mobile -->
            <div class="py-2">
              <NotificationDropdown />
            </div>

            <router-link
              to="/profile"
              @click="mobileMenuOpen = false"
              class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
            >
              Mon profil
            </router-link>
            <router-link
              to="/profile/edit"
              @click="mobileMenuOpen = false"
              class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
            >
              Parametres
            </router-link>
            <button
              @click="handleLogout"
              class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-medium text-left"
            >
              Deconnexion
            </button>
          </template>

          <template v-else>
            <router-link
              to="/login"
              @click="mobileMenuOpen = false"
              class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
            >
              Connexion
            </router-link>
            <router-link
              to="/register"
              @click="mobileMenuOpen = false"
              class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium text-center"
            >
              Inscription
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import NotificationDropdown from '@/components/NotificationDropdown.vue'

const router = useRouter()
const authStore = useAuthStore()
const themeStore = useThemeStore()
const mobileMenuOpen = ref(false)

const handleLogout = async () => {
  await authStore.logout()
  mobileMenuOpen.value = false
  router.push('/login')
}
</script>
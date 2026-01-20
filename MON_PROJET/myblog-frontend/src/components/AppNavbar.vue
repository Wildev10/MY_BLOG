<template>
  <nav class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="flex justify-between items-center h-14">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2">
          <span class="text-lg font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
            MyBlog
          </span>
        </router-link>

        <!-- Menu Desktop -->
        <div class="hidden md:flex items-center gap-1">
          <router-link
            to="/"
            :class="[
              'px-3 py-1.5 text-sm font-medium rounded-md transition-colors',
              $route.path === '/' 
                ? 'text-zinc-900 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-800' 
                : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800'
            ]"
          >
            Accueil
          </router-link>

          <template v-if="authStore.isAuthenticated">
            <router-link
              to="/create"
              :class="[
                'px-3 py-1.5 text-sm font-medium rounded-md transition-colors flex items-center gap-1.5',
                $route.path === '/create' 
                  ? 'text-zinc-900 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-800' 
                  : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Ecrire
            </router-link>

            <router-link
              to="/my-posts"
              :class="[
                'px-3 py-1.5 text-sm font-medium rounded-md transition-colors',
                $route.path === '/my-posts' 
                  ? 'text-zinc-900 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-800' 
                  : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800'
              ]"
            >
              Mes articles
            </router-link>

            <router-link
              v-if="authStore.user?.role === 'admin'"
              to="/admin"
              :class="[
                'px-3 py-1.5 text-sm font-medium rounded-md transition-colors',
                $route.path.startsWith('/admin') 
                  ? 'text-zinc-900 dark:text-zinc-50 bg-zinc-100 dark:bg-zinc-800' 
                  : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800'
              ]"
            >
              Admin
            </router-link>

            <div class="ml-2">
              <NotificationDropdown />
            </div>
            
            <button
              @click="themeStore.toggleTheme()"
              class="p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors"
            >
              <svg v-if="themeStore.isDark" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
              </svg>
              <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
              </svg>
            </button>

            <div class="relative ml-2">
              <button 
                @click="userMenuOpen = !userMenuOpen"
                class="flex items-center gap-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md p-1.5 transition-colors"
              >
                <div class="h-7 w-7 rounded-full overflow-hidden bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                  <img 
                    v-if="authStore.user?.avatar_url" 
                    :src="authStore.user.avatar_url" 
                    :alt="authStore.user?.name"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="text-zinc-600 dark:text-zinc-300 font-medium text-xs">
                    {{ authStore.user?.name?.charAt(0) || '?' }}
                  </span>
                </div>
                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <div 
                v-if="userMenuOpen"
                class="absolute right-0 mt-1 w-48 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 py-1 shadow-lg z-50"
              >
                <div class="px-3 py-2 border-b border-zinc-100 dark:border-zinc-800">
                  <p class="text-sm font-medium text-zinc-900 dark:text-zinc-50 truncate">{{ authStore.user?.name }}</p>
                  <p class="text-xs text-zinc-500 truncate">{{ authStore.user?.email }}</p>
                </div>
                <router-link
                  to="/profile"
                  @click="userMenuOpen = false"
                  class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Mon profil
                </router-link>
                <router-link
                  to="/profile/edit"
                  @click="userMenuOpen = false"
                  class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  Parametres
                </router-link>
                <hr class="my-1 border-zinc-100 dark:border-zinc-800"/>
                <button
                  @click="handleLogout"
                  class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 transition-colors"
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
              class="px-3 py-1.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors"
            >
              Connexion
            </router-link>
            <router-link
              to="/register"
              class="px-3 py-1.5 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-50 dark:text-zinc-900 rounded-md hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors"
            >
              S'inscrire
            </router-link>
          </template>
        </div>

        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="md:hidden p-2 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div v-if="mobileMenuOpen" class="md:hidden py-3 border-t border-zinc-100 dark:border-zinc-800">
        <div class="flex flex-col gap-1">
          <router-link to="/" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-md transition-colors">Accueil</router-link>
          <template v-if="authStore.isAuthenticated">
            <router-link to="/create" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-md transition-colors">Ecrire</router-link>
            <router-link to="/my-posts" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-md transition-colors">Mes articles</router-link>
            <router-link v-if="authStore.user?.role === 'admin'" to="/admin" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-md transition-colors">Admin</router-link>
            <div class="py-2 px-3"><NotificationDropdown /></div>
            <router-link to="/profile" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 hover:bg-zinc-50 dark:hover:bg-zinc-800 rounded-md transition-colors">Mon profil</router-link>
            <button @click="handleLogout" class="px-3 py-2 text-sm font-medium text-left text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-md transition-colors">Deconnexion</button>
          </template>
          <template v-else>
            <router-link to="/login" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 rounded-md">Connexion</router-link>
            <router-link to="/register" @click="mobileMenuOpen = false" class="px-3 py-2 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-50 dark:text-zinc-900 rounded-md text-center">S'inscrire</router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import NotificationDropdown from '@/components/NotificationDropdown.vue'

const router = useRouter()
const authStore = useAuthStore()
const themeStore = useThemeStore()
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)

const handleLogout = async () => {
  userMenuOpen.value = false
  mobileMenuOpen.value = false
  await authStore.logout()
  router.push('/login')
}

const handleClickOutside = (event) => {
  if (userMenuOpen.value && !event.target.closest('.relative')) {
    userMenuOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<template>
  <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
       :class="[
         scrolled 
           ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-sm shadow-slate-200/50 dark:shadow-none border-b border-slate-200/50 dark:border-slate-800/50' 
           : 'bg-transparent'
       ]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16 lg:h-20">
        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-3 group">
          <div class="relative">
            <div class="w-10 h-10 bg-gradient-to-br from-slate-900 to-slate-700 dark:from-white dark:to-slate-200 rounded-xl flex items-center justify-center shadow-lg shadow-slate-900/20 dark:shadow-none group-hover:scale-105 transition-transform duration-200">
              <span class="text-white dark:text-slate-900 font-bold text-lg">M</span>
            </div>
          </div>
          <div class="hidden sm:block">
            <span class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
              MyBlog
            </span>
            <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-medium -mt-0.5">
              Stories & Ideas
            </span>
          </div>
        </router-link>

        <!-- Menu Desktop -->
        <div class="hidden md:flex items-center gap-1">
          <router-link
            to="/"
            class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
            active-class="!text-slate-900 dark:!text-white bg-slate-100 dark:bg-slate-800"
          >
            Accueil
          </router-link>

          <template v-if="authStore.isAuthenticated">
            <router-link
              to="/create"
              class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
              active-class="!text-slate-900 dark:!text-white bg-slate-100 dark:bg-slate-800"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Écrire
            </router-link>

            <router-link
              to="/my-posts"
              class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
              active-class="!text-slate-900 dark:!text-white bg-slate-100 dark:bg-slate-800"
            >
              Mes articles
            </router-link>
            
            <router-link
              v-if="authStore.user?.role === 'admin'"
              to="/admin"
              class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
              active-class="!text-slate-900 dark:!text-white bg-slate-100 dark:bg-slate-800"
            >
              Admin
            </router-link>

            <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 mx-2"></div>

            <NotificationDropdown />
            
            <!-- Theme Toggle -->
            <button
              @click="themeStore.toggleTheme()"
              class="p-2.5 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
              aria-label="Toggle Theme"
            >
              <svg v-if="themeStore.isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
              </svg>
            </button>

            <!-- User Menu -->
            <div class="relative ml-1">
              <button 
                @click="userMenuOpen = !userMenuOpen"
                class="flex items-center gap-2.5 p-1.5 pr-3 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
              >
                <div class="h-9 w-9 rounded-full overflow-hidden bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center ring-2 ring-white dark:ring-slate-900 shadow-inner">
                  <img 
                    v-if="authStore.user?.avatar_url" 
                    :src="authStore.user.avatar_url" 
                    :alt="authStore.user?.name"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="text-slate-600 dark:text-slate-300 font-semibold text-sm">
                    {{ authStore.user?.name?.charAt(0)?.toUpperCase() || '?' }}
                  </span>
                </div>
                <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>
              
              <!-- Dropdown -->
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-2 scale-95"
                enter-to-class="opacity-100 translate-y-0 scale-100"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0 scale-100"
                leave-to-class="opacity-0 translate-y-2 scale-95"
              >
                <div 
                  v-if="userMenuOpen"
                  class="absolute right-0 mt-3 w-64 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-black/30 z-50 overflow-hidden"
                >
                  <!-- User info header -->
                  <div class="p-4 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800/50 dark:to-slate-800">
                    <div class="flex items-center gap-3">
                      <div class="h-12 w-12 rounded-xl overflow-hidden bg-white dark:bg-slate-700 flex items-center justify-center shadow-sm">
                        <img 
                          v-if="authStore.user?.avatar_url" 
                          :src="authStore.user.avatar_url" 
                          :alt="authStore.user?.name"
                          class="h-full w-full object-cover"
                        />
                        <span v-else class="text-slate-600 dark:text-slate-300 font-bold text-lg">
                          {{ authStore.user?.name?.charAt(0)?.toUpperCase() || '?' }}
                        </span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ authStore.user?.name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ authStore.user?.email }}</p>
                      </div>
                    </div>
                  </div>
                  
                  <div class="p-2">
                    <router-link
                      to="/profile"
                      @click="userMenuOpen = false"
                      class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
                    >
                      <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                      </div>
                      Mon profil
                    </router-link>
                    <router-link
                      to="/profile/edit"
                      @click="userMenuOpen = false"
                      class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
                    >
                      <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                      </div>
                      Paramètres
                    </router-link>
                  </div>
                  
                  <div class="p-2 border-t border-slate-100 dark:border-slate-800">
                    <button
                      @click="handleLogout"
                      class="flex items-center gap-3 w-full px-3 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors"
                    >
                      <div class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                      </div>
                      Déconnexion
                    </button>
                  </div>
                </div>
              </transition>
            </div>
          </template>

          <template v-else>
            <div class="flex items-center gap-3 ml-4">
              <router-link
                to="/login"
                class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors"
              >
                Connexion
              </router-link>
              <router-link
                to="/register"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-slate-900 dark:bg-white dark:text-slate-900 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-all duration-200 shadow-lg shadow-slate-900/20 dark:shadow-none hover:shadow-xl hover:-translate-y-0.5"
              >
                Commencer
              </router-link>
            </div>
          </template>
        </div>

        <!-- Mobile Menu Button -->
        <div class="flex items-center gap-2 md:hidden">
          <button
            @click="themeStore.toggleTheme()"
            class="p-2.5 text-slate-500 hover:text-slate-900 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
          >
            <svg v-if="themeStore.isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
          </button>
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="p-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
          >
            <svg class="w-6 h-6 transition-transform duration-200" :class="{ 'rotate-90': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div v-if="mobileMenuOpen" class="md:hidden pb-6 pt-2 space-y-1 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-b-2xl">
          <router-link 
            to="/" 
            @click="mobileMenuOpen = false" 
            class="flex items-center gap-3 px-4 py-3 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
          >
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Accueil
          </router-link>
          
          <template v-if="authStore.isAuthenticated">
            <router-link 
              to="/create" 
              @click="mobileMenuOpen = false" 
              class="flex items-center gap-3 px-4 py-3 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            >
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Écrire un article
            </router-link>
            <router-link 
              to="/my-posts" 
              @click="mobileMenuOpen = false" 
              class="flex items-center gap-3 px-4 py-3 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            >
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
              Mes articles
            </router-link>
            <router-link 
              v-if="authStore.user?.role === 'admin'" 
              to="/admin" 
              @click="mobileMenuOpen = false" 
              class="flex items-center gap-3 px-4 py-3 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            >
              <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
              Administration
            </router-link>
            
            <div class="my-3 mx-4 border-t border-slate-200 dark:border-slate-800"></div>
            
            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl mx-2">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                  <img 
                    v-if="authStore.user?.avatar_url" 
                    :src="authStore.user.avatar_url" 
                    :alt="authStore.user?.name"
                    class="h-full w-full object-cover"
                  />
                  <span v-else class="text-slate-600 dark:text-slate-300 font-semibold">
                    {{ authStore.user?.name?.charAt(0)?.toUpperCase() || '?' }}
                  </span>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ authStore.user?.name }}</p>
                  <p class="text-xs text-slate-500 truncate">{{ authStore.user?.email }}</p>
                </div>
              </div>
              <div class="flex gap-2 mt-3">
                <router-link 
                  to="/profile" 
                  @click="mobileMenuOpen = false" 
                  class="flex-1 text-center px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                >
                  Profil
                </router-link>
                <button 
                  @click="handleLogout" 
                  class="flex-1 text-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                >
                  Déconnexion
                </button>
              </div>
            </div>
          </template>
          
          <template v-else>
            <div class="grid grid-cols-2 gap-3 pt-4 px-2">
              <router-link 
                to="/login" 
                @click="mobileMenuOpen = false" 
                class="text-center px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
              >
                Connexion
              </router-link>
              <router-link 
                to="/register" 
                @click="mobileMenuOpen = false" 
                class="text-center px-4 py-3 text-sm font-semibold text-white bg-slate-900 dark:bg-white dark:text-slate-900 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors shadow-lg shadow-slate-900/20"
              >
                S'inscrire
              </router-link>
            </div>
          </template>
        </div>
      </transition>
    </div>
  </nav>
  
  <!-- Spacer for fixed navbar -->
  <div class="h-16 lg:h-20"></div>
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
const scrolled = ref(false)

const handleScroll = () => {
  scrolled.value = window.scrollY > 20
}

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

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  window.addEventListener('scroll', handleScroll)
  handleScroll()
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  window.removeEventListener('scroll', handleScroll)
})
</script>

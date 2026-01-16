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
            
            <!-- User menu -->
            <div class="flex items-center gap-3">
              <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                <span class="text-indigo-600 font-semibold text-sm">
                  {{ authStore.user?.name?.charAt(0) || '?' }}
                </span>
              </div>
              <button
                @click="handleLogout"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-medium text-sm"
              >
                Déconnexion
              </button>
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
              Créer un article
            </router-link>
            <router-link
              to="/my-posts"
              @click="mobileMenuOpen = false"
              class="text-gray-700 hover:text-indigo-600 font-medium transition py-2"
            >
              Mes articles
            </router-link>
            <button
              @click="handleLogout"
              class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition font-medium text-left"
            >
              Déconnexion
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

const router = useRouter()
const authStore = useAuthStore()
const mobileMenuOpen = ref(false)

const handleLogout = async () => {
  await authStore.logout()
  mobileMenuOpen.value = false
  router.push('/login')
}
</script>
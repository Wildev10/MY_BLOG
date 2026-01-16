<template>
  <div class="min-h-screen flex flex-col font-sans bg-gray-50">
    <AppNavbar v-if="!hideNavbar" />

    <!-- Main Content -->
    <main class="flex-grow flex flex-col">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
           <component :is="Component" />
        </transition>
      </router-view>
    </main>

    <AppFooter v-if="!hideNavbar" />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'

const route = useRoute()

// Optionnel : Masquer la navbar sur login/register si désiré
const hideNavbar = computed(() => {
  return false // ['/login', '/register'].includes(route.path)
})
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>

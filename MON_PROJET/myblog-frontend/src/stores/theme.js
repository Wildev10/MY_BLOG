import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useThemeStore = defineStore('theme', () => {
  // Récupérer le thème depuis localStorage (ou light par défaut)
  const isDark = ref(localStorage.getItem('theme') === 'dark')

  // Fonction pour basculer le thème
  const toggleTheme = () => {
    isDark.value = !isDark.value
  }

  // Fonction pour activer le dark mode
  const setDarkMode = () => {
    isDark.value = true
  }

  // Fonction pour activer le light mode
  const setLightMode = () => {
    isDark.value = false
  }

  // Watcher : Quand isDark change, mettre à jour le DOM et localStorage
  watch(isDark, (newValue) => {
    if (newValue) {
      document.documentElement.classList.add('dark')
      localStorage.setItem('theme', 'dark')
    } else {
      document.documentElement.classList.remove('dark')
      localStorage.setItem('theme', 'light')
    }
  }, { immediate: true }) // immediate: true = exécute au chargement

  return {
    isDark,
    toggleTheme,
    setDarkMode,
    setLightMode
  }
})
<template>
  <footer class="bg-gray-800 dark:bg-gray-950 text-white mt-auto transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- À propos -->
        <div>
          <h3 class="text-xl font-bold mb-4 text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            MyBlog
          </h3>
          <p class="text-gray-400 dark:text-gray-500 text-sm leading-relaxed">
            Plateforme de blogging moderne où vous pouvez partager vos idées, 
            découvrir des articles inspirants et rejoindre une communauté passionnée.
          </p>
          <!-- Réseaux sociaux -->
          <div class="flex gap-3 mt-4">
            <a 
              href="https://www.facebook.com/wil.deguenon" 
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 bg-gray-700 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors duration-200"
              title="Suivez-moi sur Facebook"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
            </a>
            <a 
              href="https://www.linkedin.com/in/wilfried-deguenon-05293a2ba" 
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 bg-gray-700 hover:bg-blue-700 rounded-full flex items-center justify-center transition-colors duration-200"
              title="Mon profil LinkedIn"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
            </a>
            <a 
              href="https://github.com/Wildev10" 
              target="_blank"
              rel="noopener noreferrer"
              class="w-10 h-10 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors duration-200"
              title="Mon GitHub"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Liens rapides -->
        <div>
          <h3 class="text-xl font-bold mb-4 text-white">Navigation</h3>
          <ul class="space-y-3">
            <li>
              <router-link to="/" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Accueil
              </router-link>
            </li>
            <li v-if="authStore.isAuthenticated">
              <router-link to="/create" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer un article
              </router-link>
            </li>
            <li v-if="authStore.isAuthenticated">
              <router-link to="/my-posts" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Mes articles
              </router-link>
            </li>
            <li v-if="authStore.isAuthenticated">
              <router-link to="/profile" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Mon profil
              </router-link>
            </li>
            <li v-if="!authStore.isAuthenticated">
              <router-link to="/login" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Connexion
              </router-link>
            </li>
            <li v-if="!authStore.isAuthenticated">
              <router-link to="/register" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Inscription
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 class="text-xl font-bold mb-4 text-white">Contact</h3>
          <ul class="space-y-3">
            <li>
              <a 
                href="mailto:wilfried.deguenon@epitech.eu" 
                class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2"
              >
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                wilfried.deguenon@epitech.eu
              </a>
            </li>
            <li>
              <a 
                href="tel:+22901617907 66" 
                class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition flex items-center gap-2"
              >
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                +229 01 61 79 07 66
              </a>
            </li>
            <li class="flex items-start gap-2 text-gray-400 dark:text-gray-500">
              <svg class="w-5 h-5 text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>Bénin</span>
            </li>
          </ul>
          
          <!-- Bouton contact -->
          <button
            @click="openContactModal"
            class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-medium transition flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Nous contacter
          </button>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-xl font-bold mb-4 text-white">Newsletter</h3>
          <p class="text-gray-400 dark:text-gray-500 text-sm mb-4">
            Recevez nos derniers articles directement dans votre boîte mail.
          </p>
          <form @submit.prevent="subscribeNewsletter" class="space-y-3">
            <input
              v-model="newsletterEmail"
              type="email"
              placeholder="Votre email"
              required
              class="w-full px-4 py-2 bg-gray-700 dark:bg-gray-800 border border-gray-600 dark:border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
            />
            <button
              type="submit"
              :disabled="subscribing"
              class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white py-2 px-4 rounded-lg font-medium transition flex items-center justify-center gap-2"
            >
              <svg v-if="subscribing" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <span v-else>S'abonner</span>
            </button>
          </form>
          <p v-if="subscribeMessage" :class="subscribeSuccess ? 'text-green-400' : 'text-red-400'" class="text-sm mt-2">
            {{ subscribeMessage }}
          </p>
        </div>
      </div>

      <!-- Copyright -->
      <div class="border-t border-gray-700 dark:border-gray-800 mt-10 pt-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
          <p class="text-gray-400 dark:text-gray-500 text-sm">
            &copy; {{ currentYear }} MyBlog. Tous droits réservés.
          </p>
          <div class="flex gap-6 text-sm">
            <a href="#" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition">
              Mentions légales
            </a>
            <a href="#" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition">
              Politique de confidentialité
            </a>
            <a href="#" class="text-gray-400 dark:text-gray-500 hover:text-indigo-400 transition">
              CGU
            </a>
          </div>
        </div>
        <p class="text-center text-gray-500 dark:text-gray-600 text-xs mt-4">
          Fait avec Vue.js & Laravel
        </p>
      </div>
    </div>

    <!-- Modal de contact -->
    <Teleport to="body">
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showContactModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
          <div 
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-6"
            @click.stop
          >
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-xl font-bold text-gray-900 dark:text-white">Nous contacter</h3>
              <button @click="closeContactModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="sendContactMessage" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
                <input
                  v-model="contactForm.name"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                  placeholder="Votre nom"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input
                  v-model="contactForm.email"
                  type="email"
                  required
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                  placeholder="votre@email.com"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sujet</label>
                <select
                  v-model="contactForm.subject"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                >
                  <option value="general">Question générale</option>
                  <option value="bug">Signaler un bug</option>
                  <option value="suggestion">Suggestion</option>
                  <option value="partnership">Partenariat</option>
                  <option value="other">Autre</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                <textarea
                  v-model="contactForm.message"
                  required
                  rows="4"
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 resize-none"
                  placeholder="Votre message..."
                ></textarea>
              </div>
              
              <p v-if="contactMessage" :class="contactSuccess ? 'text-green-600' : 'text-red-600'" class="text-sm">
                {{ contactMessage }}
              </p>
              
              <button
                type="submit"
                :disabled="sendingContact"
                class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white py-3 px-4 rounded-lg font-semibold transition flex items-center justify-center gap-2"
              >
                <svg v-if="sendingContact" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span>{{ sendingContact ? 'Envoi...' : 'Envoyer le message' }}</span>
              </button>
            </form>
          </div>
        </div>
      </transition>
    </Teleport>
  </footer>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()
const currentYear = computed(() => new Date().getFullYear())

// Newsletter
const newsletterEmail = ref('')
const subscribing = ref(false)
const subscribeMessage = ref('')
const subscribeSuccess = ref(false)

const subscribeNewsletter = async () => {
  subscribing.value = true
  subscribeMessage.value = ''
  
  try {
    // Simuler un appel API (à remplacer par votre vraie API)
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    subscribeSuccess.value = true
    subscribeMessage.value = 'Merci pour votre inscription !'
    newsletterEmail.value = ''
    
    setTimeout(() => {
      subscribeMessage.value = ''
    }, 3000)
  } catch (error) {
    subscribeSuccess.value = false
    subscribeMessage.value = 'Une erreur est survenue. Réessayez.'
  } finally {
    subscribing.value = false
  }
}

// Modal contact
const showContactModal = ref(false)
const sendingContact = ref(false)
const contactMessage = ref('')
const contactSuccess = ref(false)

const contactForm = reactive({
  name: '',
  email: '',
  subject: 'general',
  message: ''
})

const openContactModal = () => {
  showContactModal.value = true
  // Pré-remplir si connecté
  if (authStore.user) {
    contactForm.name = authStore.user.name || ''
    contactForm.email = authStore.user.email || ''
  }
}

const closeContactModal = () => {
  showContactModal.value = false
  contactMessage.value = ''
}

const sendContactMessage = async () => {
  sendingContact.value = true
  contactMessage.value = ''
  
  try {
    // Option 1: Ouvrir le client mail
    const mailtoLink = `mailto:wilfried.deguenon@epitech.eu?subject=${encodeURIComponent(getSubjectLabel(contactForm.subject) + ': De ' + contactForm.name)}&body=${encodeURIComponent(contactForm.message + '\n\n---\nDe: ' + contactForm.name + '\nEmail: ' + contactForm.email)}`
    window.location.href = mailtoLink
    
    contactSuccess.value = true
    contactMessage.value = 'Votre client mail va s\'ouvrir...'
    
    setTimeout(() => {
      closeContactModal()
      // Réinitialiser le formulaire
      contactForm.name = ''
      contactForm.email = ''
      contactForm.subject = 'general'
      contactForm.message = ''
    }, 1500)
  } catch (error) {
    contactSuccess.value = false
    contactMessage.value = 'Une erreur est survenue.'
  } finally {
    sendingContact.value = false
  }
}

const getSubjectLabel = (value) => {
  const subjects = {
    general: 'Question générale',
    bug: 'Signalement de bug',
    suggestion: 'Suggestion',
    partnership: 'Partenariat',
    other: 'Autre'
  }
  return subjects[value] || 'Contact'
}
</script>
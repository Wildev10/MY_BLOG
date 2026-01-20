<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 py-12 px-4 transition-colors">
    <div class="max-w-2xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <router-link to="/profile" class="inline-flex items-center text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-50 transition">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Retour
        </router-link>
        <h1 class="mt-4 text-2xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">Modifier mon profil</h1>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Avatar -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6">
          <h2 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 mb-4">Photo de profil</h2>
          <div class="flex items-center gap-6">
            <AvatarUpload
              :avatar-url="form.avatar_url"
              :name="form.name"
              size="xl"
              :editable="true"
              :has-custom-avatar="!!form.avatar"
              :uploading="uploadingAvatar"
              @upload="handleAvatarUpload"
              @delete="handleAvatarDelete"
            />
            <div class="text-xs text-zinc-400">
              <p>Formats: JPG, PNG, GIF, WEBP</p>
              <p>Max: 2 Mo</p>
            </div>
          </div>
        </div>

        <!-- Informations -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6">
          <h2 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 mb-4">Informations</h2>
          
          <div class="space-y-4">
            <!-- Nom -->
            <div>
              <label for="name" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
                Nom *
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
                :class="errors.name ? 'border-red-300 dark:border-red-700' : 'border-zinc-200 dark:border-zinc-700'"
              />
              <p v-if="errors.name" class="mt-1 text-xs text-red-500">{{ errors.name[0] }}</p>
            </div>

            <!-- Username -->
            <div>
              <label for="username" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
                Username
              </label>
              <div class="flex">
                <span class="inline-flex items-center px-3 text-sm text-zinc-400 bg-zinc-100 dark:bg-zinc-800 border border-r-0 border-zinc-200 dark:border-zinc-700 rounded-l-md">@</span>
                <input
                  id="username"
                  v-model="form.username"
                  type="text"
                  class="flex-1 px-3 py-2 border rounded-r-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
                  :class="errors.username ? 'border-red-300 dark:border-red-700' : 'border-zinc-200 dark:border-zinc-700'"
                  placeholder="mon-pseudo"
                />
              </div>
              <p v-if="errors.username" class="mt-1 text-xs text-red-500">{{ errors.username[0] }}</p>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
                Email *
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="w-full px-3 py-2 border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
                :class="errors.email ? 'border-red-300 dark:border-red-700' : 'border-zinc-200 dark:border-zinc-700'"
              />
              <p v-if="errors.email" class="mt-1 text-xs text-red-500">{{ errors.email[0] }}</p>
            </div>

            <!-- Bio -->
            <div>
              <label for="bio" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
                Bio
              </label>
              <textarea
                id="bio"
                v-model="form.bio"
                rows="3"
                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition resize-none"
                placeholder="Quelques mots sur vous..."
              ></textarea>
              <p class="mt-1 text-xs text-zinc-400 text-right">{{ form.bio?.length || 0 }}/500</p>
            </div>

            <!-- Location -->
            <div>
              <label for="location" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
                Localisation
              </label>
              <input
                id="location"
                v-model="form.location"
                type="text"
                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
                placeholder="Paris, France"
              />
            </div>
          </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end gap-3">
          <router-link
            to="/profile"
            class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700 rounded-md hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
          >
            Annuler
          </router-link>
          <button
            type="submit"
            :disabled="saving"
            class="px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 rounded-md hover:bg-zinc-800 dark:hover:bg-zinc-200 disabled:opacity-50 transition"
          >
            {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
          </button>
        </div>
      </form>

      <!-- Mot de passe -->
      <div class="mt-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6">
        <h2 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 mb-4">Changer le mot de passe</h2>
        
        <form @submit.prevent="handlePasswordChange" class="space-y-4">
          <div>
            <label for="current_password" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Mot de passe actuel
            </label>
            <input
              id="current_password"
              v-model="passwordForm.current_password"
              type="password"
              required
              class="w-full px-3 py-2 border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
              :class="passwordErrors.current_password ? 'border-red-300 dark:border-red-700' : 'border-zinc-200 dark:border-zinc-700'"
            />
            <p v-if="passwordErrors.current_password" class="mt-1 text-xs text-red-500">{{ passwordErrors.current_password[0] }}</p>
          </div>

          <div>
            <label for="password" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Nouveau mot de passe
            </label>
            <input
              id="password"
              v-model="passwordForm.password"
              type="password"
              required
              class="w-full px-3 py-2 border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
              :class="passwordErrors.password ? 'border-red-300 dark:border-red-700' : 'border-zinc-200 dark:border-zinc-700'"
            />
            <p v-if="passwordErrors.password" class="mt-1 text-xs text-red-500">{{ passwordErrors.password[0] }}</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-1.5">
              Confirmer
            </label>
            <input
              id="password_confirmation"
              v-model="passwordForm.password_confirmation"
              type="password"
              required
              class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 text-sm focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-50 focus:border-transparent transition"
            />
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="changingPassword"
              class="px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 bg-zinc-900 dark:bg-zinc-50 rounded-md hover:bg-zinc-800 dark:hover:bg-zinc-200 disabled:opacity-50 transition"
            >
              {{ changingPassword ? 'Modification...' : 'Changer' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Toast -->
      <Teleport to="body">
        <div
          v-if="showSuccessMessage"
          class="fixed bottom-4 right-4 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 px-4 py-3 rounded-md text-sm font-medium z-50 flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          {{ successMessage }}
        </div>
      </Teleport>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { getProfile, updateProfile, updatePassword, uploadAvatar, deleteAvatar } from '@/services/api'
import AvatarUpload from '@/components/AvatarUpload.vue'

const authStore = useAuthStore()

const form = reactive({
  name: '',
  username: '',
  email: '',
  bio: '',
  location: '',
  avatar: null,
  avatar_url: ''
})

const passwordForm = reactive({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const errors = ref({})
const passwordErrors = ref({})
const saving = ref(false)
const changingPassword = ref(false)
const uploadingAvatar = ref(false)
const showSuccessMessage = ref(false)
const successMessage = ref('')

const loadProfile = async () => {
  try {
    const response = await getProfile()
    const user = response.data.user
    form.name = user.name || ''
    form.username = user.username || ''
    form.email = user.email || ''
    form.bio = user.bio || ''
    form.location = user.location || ''
    form.avatar = user.avatar
    form.avatar_url = user.avatar_url
  } catch (error) {
    console.error('Erreur:', error)
  }
}

const showSuccess = (message) => {
  successMessage.value = message
  showSuccessMessage.value = true
  setTimeout(() => {
    showSuccessMessage.value = false
  }, 3000)
}

const handleSubmit = async () => {
  try {
    saving.value = true
    errors.value = {}

    const response = await updateProfile({
      name: form.name,
      username: form.username,
      email: form.email,
      bio: form.bio,
      location: form.location
    })

    authStore.user = { ...authStore.user, ...response.data.user }
    showSuccess('Profil mis a jour')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      alert('Erreur lors de la mise a jour')
    }
  } finally {
    saving.value = false
  }
}

const handlePasswordChange = async () => {
  try {
    changingPassword.value = true
    passwordErrors.value = {}

    await updatePassword(passwordForm)

    passwordForm.current_password = ''
    passwordForm.password = ''
    passwordForm.password_confirmation = ''

    showSuccess('Mot de passe modifie')
  } catch (error) {
    if (error.response?.status === 422) {
      passwordErrors.value = error.response.data.errors || {}
    } else {
      alert('Erreur lors du changement')
    }
  } finally {
    changingPassword.value = false
  }
}

const handleAvatarUpload = async (file) => {
  try {
    uploadingAvatar.value = true
    const response = await uploadAvatar(file)
    form.avatar = response.data.avatar
    form.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: form.avatar, avatar_url: form.avatar_url }
    showSuccess('Avatar mis a jour')
  } catch (error) {
    console.error('Erreur:', error)
    alert(error.response?.data?.message || "Erreur lors de l'upload")
  } finally {
    uploadingAvatar.value = false
  }
}

const handleAvatarDelete = async () => {
  if (!confirm('Supprimer votre avatar ?')) return
  
  try {
    const response = await deleteAvatar()
    form.avatar = null
    form.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: null, avatar_url: form.avatar_url }
    showSuccess('Avatar supprime')
  } catch (error) {
    console.error('Erreur:', error)
    alert('Erreur lors de la suppression')
  }
}

onMounted(() => {
  loadProfile()
})
</script>

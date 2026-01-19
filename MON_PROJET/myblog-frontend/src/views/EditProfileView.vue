<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
      <!-- Header -->
      <div class="mb-8">
        <router-link to="/profile" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Retour au profil
        </router-link>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Modifier mon profil</h1>
        <p class="mt-1 text-gray-500">Mettez a jour vos informations personnelles.</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Avatar -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Photo de profil</h2>
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
            <div class="text-sm text-gray-500">
              <p>Formats acceptes: JPG, PNG, GIF, WEBP</p>
              <p>Taille maximale: 2 Mo</p>
            </div>
          </div>
        </div>

        <!-- Informations de base -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h2>
          
          <div class="space-y-4">
            <!-- Nom -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">
                Nom complet <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.name }"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
            </div>

            <!-- Username -->
            <div>
              <label for="username" class="block text-sm font-medium text-gray-700">
                Nom d'utilisateur
              </label>
              <div class="mt-1 flex rounded-md shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                  @
                </span>
                <input
                  id="username"
                  v-model="form.username"
                  type="text"
                  class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.username }"
                  placeholder="mon-pseudo"
                />
              </div>
              <p class="mt-1 text-xs text-gray-500">Lettres, chiffres, tirets et underscores uniquement</p>
              <p v-if="errors.username" class="mt-1 text-sm text-red-600">{{ errors.username[0] }}</p>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">
                Adresse e-mail <span class="text-red-500">*</span>
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.email }"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
            </div>
          </div>
        </div>

        <!-- Infos supplementaires -->
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-lg font-medium text-gray-900 mb-4">Informations supplementaires</h2>
          
          <div class="space-y-4">
            <!-- Bio -->
            <div>
              <label for="bio" class="block text-sm font-medium text-gray-700">
                Bio
              </label>
              <textarea
                id="bio"
                v-model="form.bio"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                :class="{ 'border-red-300': errors.bio }"
                placeholder="Parlez-nous un peu de vous..."
              ></textarea>
              <div class="mt-1 flex justify-between text-xs">
                <p v-if="errors.bio" class="text-red-600">{{ errors.bio[0] }}</p>
                <p class="text-gray-500 ml-auto">{{ form.bio?.length || 0 }} / 500</p>
              </div>
            </div>

            <!-- Location -->
            <div>
              <label for="location" class="block text-sm font-medium text-gray-700">
                Localisation
              </label>
              <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <input
                  id="location"
                  v-model="form.location"
                  type="text"
                  class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                  :class="{ 'border-red-300': errors.location }"
                  placeholder="Paris, France"
                />
              </div>
              <p v-if="errors.location" class="mt-1 text-sm text-red-600">{{ errors.location[0] }}</p>
            </div>
          </div>
        </div>

        <!-- Boutons de soumission -->
        <div class="flex justify-end gap-3">
          <router-link
            to="/profile"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Annuler
          </router-link>
          <button
            type="submit"
            :disabled="saving"
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="saving">Enregistrement...</span>
            <span v-else>Enregistrer les modifications</span>
          </button>
        </div>
      </form>

      <!-- Changer le mot de passe -->
      <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Changer le mot de passe</h2>
        
        <form @submit.prevent="handlePasswordChange" class="space-y-4">
          <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">
              Mot de passe actuel
            </label>
            <input
              id="current_password"
              v-model="passwordForm.current_password"
              type="password"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              :class="{ 'border-red-300': passwordErrors.current_password }"
            />
            <p v-if="passwordErrors.current_password" class="mt-1 text-sm text-red-600">{{ passwordErrors.current_password[0] }}</p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Nouveau mot de passe
            </label>
            <input
              id="password"
              v-model="passwordForm.password"
              type="password"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              :class="{ 'border-red-300': passwordErrors.password }"
            />
            <p class="mt-1 text-xs text-gray-500">Minimum 8 caracteres, avec majuscules, minuscules et chiffres</p>
            <p v-if="passwordErrors.password" class="mt-1 text-sm text-red-600">{{ passwordErrors.password[0] }}</p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirmer le nouveau mot de passe
            </label>
            <input
              id="password_confirmation"
              v-model="passwordForm.password_confirmation"
              type="password"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="changingPassword"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="changingPassword">Modification...</span>
              <span v-else>Changer le mot de passe</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Message de succes -->
      <Teleport to="body">
        <div
          v-if="showSuccessMessage"
          class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

// Charger le profil
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
    console.error('Erreur lors du chargement du profil:', error)
  }
}

// Afficher un message de succes
const showSuccess = (message) => {
  successMessage.value = message
  showSuccessMessage.value = true
  setTimeout(() => {
    showSuccessMessage.value = false
  }, 3000)
}

// Soumettre le formulaire de profil
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

    // Mettre a jour le store
    authStore.user = { ...authStore.user, ...response.data.user }
    
    showSuccess('Profil mis a jour avec succes')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
    } else {
      alert('Erreur lors de la mise a jour du profil')
    }
  } finally {
    saving.value = false
  }
}

// Changer le mot de passe
const handlePasswordChange = async () => {
  try {
    changingPassword.value = true
    passwordErrors.value = {}

    await updatePassword(passwordForm)

    // Reset le formulaire
    passwordForm.current_password = ''
    passwordForm.password = ''
    passwordForm.password_confirmation = ''

    showSuccess('Mot de passe modifie avec succes')
  } catch (error) {
    if (error.response?.status === 422) {
      passwordErrors.value = error.response.data.errors || {}
    } else {
      alert('Erreur lors du changement de mot de passe')
    }
  } finally {
    changingPassword.value = false
  }
}

// Upload d'avatar
const handleAvatarUpload = async (file) => {
  try {
    uploadingAvatar.value = true
    const response = await uploadAvatar(file)
    form.avatar = response.data.avatar
    form.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: form.avatar, avatar_url: form.avatar_url }
    showSuccess('Avatar mis a jour')
  } catch (error) {
    console.error('Erreur lors de l\'upload:', error)
    alert(error.response?.data?.message || 'Erreur lors de l\'upload de l\'avatar')
  } finally {
    uploadingAvatar.value = false
  }
}

// Supprimer l'avatar
const handleAvatarDelete = async () => {
  if (!confirm('Voulez-vous vraiment supprimer votre avatar ?')) return
  
  try {
    const response = await deleteAvatar()
    form.avatar = null
    form.avatar_url = response.data.avatar_url
    authStore.user = { ...authStore.user, avatar: null, avatar_url: form.avatar_url }
    showSuccess('Avatar supprime')
  } catch (error) {
    console.error('Erreur lors de la suppression:', error)
    alert('Erreur lors de la suppression de l\'avatar')
  }
}

onMounted(() => {
  loadProfile()
})
</script>

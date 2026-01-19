<template>
  <div class="relative">
    <!-- Avatar actuel -->
    <div class="relative group">
      <img
        :src="avatarUrl"
        :alt="name"
        :class="sizeClasses"
        class="rounded-full object-cover border-4 border-white shadow-lg"
      />
      
      <!-- Overlay pour changer l'avatar -->
      <div
        v-if="editable"
        @click="triggerFileInput"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
      >
        <div class="text-center text-white">
          <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          <span class="text-xs mt-1 block">Changer</span>
        </div>
      </div>

      <!-- Loader -->
      <div
        v-if="uploading"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full"
      >
        <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <!-- Input file caché -->
    <input
      ref="fileInput"
      type="file"
      accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
      class="hidden"
      @change="handleFileChange"
    />

    <!-- Bouton supprimer (si avatar personnalisé) -->
    <button
      v-if="editable && hasCustomAvatar"
      @click="handleDelete"
      type="button"
      class="absolute -bottom-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition"
      title="Supprimer l'avatar"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- Message d'erreur -->
    <p v-if="error" class="mt-2 text-sm text-red-600 text-center">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  avatarUrl: {
    type: String,
    required: true
  },
  name: {
    type: String,
    default: 'User'
  },
  size: {
    type: String,
    default: 'lg', // sm, md, lg, xl
    validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
  },
  editable: {
    type: Boolean,
    default: false
  },
  hasCustomAvatar: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['upload', 'delete'])

const fileInput = ref(null)
const uploading = ref(false)
const error = ref('')

// Classes de taille
const sizeClasses = computed(() => {
  const sizes = {
    sm: 'w-12 h-12',
    md: 'w-20 h-20',
    lg: 'w-32 h-32',
    xl: 'w-40 h-40'
  }
  return sizes[props.size]
})

// Ouvrir le sélecteur de fichier
const triggerFileInput = () => {
  if (!uploading.value) {
    fileInput.value?.click()
  }
}

// Gérer le changement de fichier
const handleFileChange = async (event) => {
  const file = event.target.files[0]
  if (!file) return

  error.value = ''

  // Valider le type
  const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp']
  if (!validTypes.includes(file.type)) {
    error.value = 'Format non supporté. Utilisez JPG, PNG, GIF ou WebP.'
    return
  }

  // Valider la taille (2MB max)
  if (file.size > 2 * 1024 * 1024) {
    error.value = 'L\'image ne doit pas dépasser 2 Mo.'
    return
  }

  uploading.value = true
  try {
    emit('upload', file)
  } finally {
    uploading.value = false
    // Reset l'input
    event.target.value = ''
  }
}

// Gérer la suppression
const handleDelete = () => {
  if (confirm('Supprimer votre avatar ?')) {
    emit('delete')
  }
}

// Exposer pour le parent
defineExpose({
  setUploading: (value) => { uploading.value = value },
  setError: (message) => { error.value = message }
})
</script>

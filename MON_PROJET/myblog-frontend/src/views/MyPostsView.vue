<template>
  <div class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
      
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
        <div>
          <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">
            Mes Articles
          </h1>
          <p class="mt-2 text-gray-600">Gérez vos publications et partagez vos idées avec le monde.</p>
        </div>
        <router-link 
          to="/create" 
          class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"
        >
          <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Nouvel article
        </router-link>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="n in 6" :key="n" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-pulse">
           <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
           <div class="h-6 bg-gray-200 rounded w-3/4 mb-4"></div>
           <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
           <div class="h-4 bg-gray-200 rounded w-5/6 mb-6"></div>
           <div class="flex justify-between mt-auto">
             <div class="h-8 bg-gray-200 rounded w-20"></div>
             <div class="h-8 bg-gray-200 rounded w-20"></div>
           </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r shadow-md">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-red-700">{{ errorMessage }}</p>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="posts.length === 0" class="text-center py-20 bg-white rounded-3xl shadow-sm border border-dashed border-gray-300">
         <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 mb-6">
            <svg class="h-10 w-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
         </div>
         <h3 class="text-xl font-medium text-gray-900 mb-2">Vous n'avez pas encore d'articles</h3>
         <p class="text-gray-500 mb-8 max-w-md mx-auto">Commencez à écrire dès aujourd'hui et partagez votre premier article avec notre communauté.</p>
         <router-link to="/create" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors">
            Créer mon premier article
         </router-link>
      </div>

      <!-- Posts Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="post in posts" 
          :key="post.id" 
          class="bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 hover:border-indigo-100 transition-all duration-300 flex flex-col group overflow-hidden"
        >
          <!-- Card Content -->
          <div class="p-6 flex-grow relative">
             <!-- Decorative top bar -->
             <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-indigo-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>

             <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
               <span class="flex items-center">
                 <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                 Nice date format here
               </span>
               <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full" v-if="post.published">Publié</span>
               <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full" v-else>Brouillon</span>
             </div>

             <router-link :to="{ name: 'post-detail', params: { id: post.id } }" class="block group-hover:text-indigo-600 transition-colors">
               <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                 {{ post.title }}
               </h3>
             </router-link>

             <!-- Badge Catégorie -->
             <span 
               v-if="post.category" 
               class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mb-3"
               :style="{ backgroundColor: post.category.color + '20', color: post.category.color }"
             >
               {{ post.category.name }}
             </span>

             <p class="text-gray-600 line-clamp-3 mb-6">
               {{ post.content }}
             </p>
          </div>

          <!-- Actions Footer -->
          <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
             <router-link 
               :to="{ name: 'post-detail', params: { id: post.id } }"
               class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors"
             >
               Voir l'article
             </router-link>
             
             <div class="flex items-center gap-3">
               <router-link 
                 :to="{ name: 'post-edit', params: { id: post.id } }" 
                 class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                 title="Modifier"
               >
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
               </router-link>
               <button 
                 @click="confirmDelete(post.id)" 
                 class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                 title="Supprimer"
               >
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
               </button>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getMyPosts, deletePost } from '@/services/api'

const posts = ref([])
const loading = ref(true)
const errorMessage = ref('')

const fetchPosts = async () => {
    loading.value = true
    try {
        const response = await getMyPosts()
        // Handle pagination response from Laravel
        // response.data.data is the paginator object, response.data.data.data is the array of posts
        if (response.data.data && response.data.data.data) {
             posts.value = response.data.data.data
        } else {
             posts.value = response.data.data || []
        }
    } catch (error) {
        errorMessage.value = "Impossible de récupérer vos articles."
    } finally {
        loading.value = false
    }
}

const confirmDelete = async (id) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.")) {
        try {
            await deletePost(id)
            // Remove from list immediately
            posts.value = posts.value.filter(p => p.id !== id)
        } catch (error) {
            alert("Erreur lors de la suppression")
        }
    }
}

onMounted(() => {
    fetchPosts()
})
</script>

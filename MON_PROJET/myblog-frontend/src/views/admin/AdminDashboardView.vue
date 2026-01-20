<template>
  <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <AdminSidebar />
        </div>

        <!-- Contenu principal -->
        <div class="lg:col-span-3">
          <!-- Header -->
          <div class="mb-6">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
              Vue d'ensemble
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
              Tableau de bord de la plateforme
            </p>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="flex justify-center items-center py-20">
            <svg class="animate-spin h-6 w-6 text-zinc-400" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>

          <div v-else class="space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
              <!-- Utilisateurs -->
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                <div class="flex items-center justify-between mb-3">
                  <span class="text-[13px] text-zinc-500 dark:text-zinc-400 font-medium">Utilisateurs</span>
                  <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                  </svg>
                </div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
                  {{ stats?.users?.total || 0 }}
                </div>
                <div class="mt-1 text-xs text-zinc-500">
                  <span class="text-emerald-600 dark:text-emerald-400">+{{ stats?.users?.new_this_month || 0 }}</span> ce mois
                </div>
              </div>

              <!-- Articles -->
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                <div class="flex items-center justify-between mb-3">
                  <span class="text-[13px] text-zinc-500 dark:text-zinc-400 font-medium">Articles</span>
                  <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
                  {{ stats?.posts?.total || 0 }}
                </div>
                <div class="mt-1 text-xs text-zinc-500">
                  <span class="text-emerald-600 dark:text-emerald-400">+{{ stats?.posts?.published_today || 0 }}</span> aujourd'hui
                </div>
              </div>

              <!-- Commentaires -->
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                <div class="flex items-center justify-between mb-3">
                  <span class="text-[13px] text-zinc-500 dark:text-zinc-400 font-medium">Commentaires</span>
                  <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                </div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
                  {{ stats?.comments?.total || 0 }}
                </div>
                <div class="mt-1 text-xs text-zinc-500">
                  <span class="text-emerald-600 dark:text-emerald-400">+{{ stats?.comments?.today || 0 }}</span> aujourd'hui
                </div>
              </div>

              <!-- Likes -->
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                <div class="flex items-center justify-between mb-3">
                  <span class="text-[13px] text-zinc-500 dark:text-zinc-400 font-medium">Likes</span>
                  <svg class="w-4 h-4 text-zinc-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                  </svg>
                </div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50 tracking-tight">
                  {{ stats?.likes?.total || 0 }}
                </div>
                <div class="mt-1 text-xs text-zinc-500">
                  <span class="text-emerald-600 dark:text-emerald-400">+{{ stats?.likes?.today || 0 }}</span> aujourd'hui
                </div>
              </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 mb-4">
                  Articles publies (7 derniers jours)
                </h3>
                <div class="h-48">
                  <canvas ref="postsChart"></canvas>
                </div>
              </div>

              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-5">
                <h3 class="text-sm font-medium text-zinc-900 dark:text-zinc-50 mb-4">
                  Nouvelles inscriptions (7 derniers jours)
                </h3>
                <div class="h-48">
                  <canvas ref="usersChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Liens rapides -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <router-link
                to="/admin/users"
                class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors group"
              >
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-950 transition-colors">
                    <svg class="w-5 h-5 text-zinc-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-50">Gerer les utilisateurs</p>
                    <p class="text-xs text-zinc-500">Promouvoir, bannir</p>
                  </div>
                </div>
              </router-link>

              <router-link
                to="/admin/posts"
                class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors group"
              >
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 dark:group-hover:bg-emerald-950 transition-colors">
                    <svg class="w-5 h-5 text-zinc-500 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-50">Moderer les articles</p>
                    <p class="text-xs text-zinc-500">Supprimer, modifier</p>
                  </div>
                </div>
              </router-link>

              <router-link
                to="/admin/categories"
                class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors group"
              >
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center group-hover:bg-violet-50 dark:group-hover:bg-violet-950 transition-colors">
                    <svg class="w-5 h-5 text-zinc-500 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-50">Gerer les categories</p>
                    <p class="text-xs text-zinc-500">Creer, modifier</p>
                  </div>
                </div>
              </router-link>
            </div>

            <!-- Details supplementaires -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-xs text-zinc-500 mb-1">Admins</p>
                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ stats?.users?.admins || 0 }}</p>
              </div>
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-xs text-zinc-500 mb-1">Bannis</p>
                <p class="text-lg font-semibold text-red-600 dark:text-red-400">{{ stats?.users?.banned || 0 }}</p>
              </div>
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-xs text-zinc-500 mb-1">Avec images</p>
                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ stats?.posts?.with_images || 0 }}</p>
              </div>
              <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                <p class="text-xs text-zinc-500 mb-1">Categories</p>
                <p class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">{{ stats?.categories?.total || 0 }}</p>
              </div>
            </div>

            <!-- Section Articles -->
            <div v-if="posts" class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
              <!-- Tabs -->
              <div class="flex border-b border-zinc-200 dark:border-zinc-800">
                <button
                  @click="activeTab = 'recent'"
                  :class="[
                    'px-4 py-3 text-sm font-medium transition-colors',
                    activeTab === 'recent' 
                      ? 'text-zinc-900 dark:text-zinc-50 border-b-2 border-zinc-900 dark:border-zinc-50' 
                      : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'
                  ]"
                >
                  Plus récents
                </button>
                <button
                  @click="activeTab = 'popular'"
                  :class="[
                    'px-4 py-3 text-sm font-medium transition-colors',
                    activeTab === 'popular' 
                      ? 'text-zinc-900 dark:text-zinc-50 border-b-2 border-zinc-900 dark:border-zinc-50' 
                      : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'
                  ]"
                >
                  Plus populaires
                </button>
                <button
                  @click="activeTab = 'oldest'"
                  :class="[
                    'px-4 py-3 text-sm font-medium transition-colors',
                    activeTab === 'oldest' 
                      ? 'text-zinc-900 dark:text-zinc-50 border-b-2 border-zinc-900 dark:border-zinc-50' 
                      : 'text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300'
                  ]"
                >
                  Plus anciens
                </button>
              </div>

              <!-- Liste des articles -->
              <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <template v-if="activeTab === 'recent'">
                  <div 
                    v-for="post in posts.recent" 
                    :key="post.id" 
                    @click="goToPost(post.id)"
                    class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex-1 min-w-0">
                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50 truncate block">
                          {{ post.title }}
                        </span>
                        <div class="flex items-center gap-3 mt-1">
                          <span class="text-xs text-zinc-500">{{ post.user?.name }}</span>
                          <span v-if="post.category" class="text-xs px-1.5 py-0.5 rounded" :style="{ backgroundColor: post.category.color + '15', color: post.category.color }">
                            {{ post.category.name }}
                          </span>
                        </div>
                      </div>
                      <div class="flex items-center gap-4 text-xs text-zinc-400 ml-4">
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                          {{ post.likes_count }}
                        </span>
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                          {{ post.comments_count }}
                        </span>
                        <span>{{ formatDate(post.created_at) }}</span>
                        <svg class="w-4 h-4 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-if="activeTab === 'popular'">
                  <div 
                    v-for="post in posts.popular" 
                    :key="post.id" 
                    @click="goToPost(post.id)"
                    class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex-1 min-w-0">
                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50 truncate block">
                          {{ post.title }}
                        </span>
                        <div class="flex items-center gap-3 mt-1">
                          <span class="text-xs text-zinc-500">{{ post.user?.name }}</span>
                          <span v-if="post.category" class="text-xs px-1.5 py-0.5 rounded" :style="{ backgroundColor: post.category.color + '15', color: post.category.color }">
                            {{ post.category.name }}
                          </span>
                        </div>
                      </div>
                      <div class="flex items-center gap-4 text-xs text-zinc-400 ml-4">
                        <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
                          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                          {{ post.likes_count }}
                        </span>
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                          {{ post.comments_count }}
                        </span>
                        <span>{{ formatDate(post.created_at) }}</span>
                        <svg class="w-4 h-4 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-if="activeTab === 'oldest'">
                  <div 
                    v-for="post in posts.oldest" 
                    :key="post.id" 
                    @click="goToPost(post.id)"
                    class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex-1 min-w-0">
                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50 truncate block">
                          {{ post.title }}
                        </span>
                        <div class="flex items-center gap-3 mt-1">
                          <span class="text-xs text-zinc-500">{{ post.user?.name }}</span>
                          <span v-if="post.category" class="text-xs px-1.5 py-0.5 rounded" :style="{ backgroundColor: post.category.color + '15', color: post.category.color }">
                            {{ post.category.name }}
                          </span>
                        </div>
                      </div>
                      <div class="flex items-center gap-4 text-xs text-zinc-400 ml-4">
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                          {{ post.likes_count }}
                        </span>
                        <span class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                          {{ post.comments_count }}
                        </span>
                        <span>{{ formatDate(post.created_at) }}</span>
                        <svg class="w-4 h-4 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </div>
                    </div>
                  </div>
                </template>

                <!-- Message si pas d'articles -->
                <div v-if="(activeTab === 'recent' && (!posts.recent || posts.recent.length === 0)) ||
                           (activeTab === 'popular' && (!posts.popular || posts.popular.length === 0)) ||
                           (activeTab === 'oldest' && (!posts.oldest || posts.oldest.length === 0))"
                     class="p-8 text-center text-sm text-zinc-400">
                  Aucun article
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { getAdminStats } from '@/services/api'
import Chart from 'chart.js/auto'
import AdminSidebar from '@/components/AdminSidebar.vue'

const router = useRouter()

const stats = ref(null)
const charts = ref(null)
const posts = ref(null)
const loading = ref(true)
const activeTab = ref('recent')
const postsChart = ref(null)
const usersChart = ref(null)
let postsChartInstance = null
let usersChartInstance = null

const goToPost = (postId) => {
  router.push(`/posts/${postId}`)
}

const fetchStats = async () => {
  try {
    const response = await getAdminStats()
    stats.value = response.data.data.stats
    charts.value = response.data.data.charts
    posts.value = response.data.data.posts
    await nextTick()
    createCharts()
  } catch (error) {
    console.error('Erreur:', error)
    if (error.response?.status === 403) {
      alert('Acces refuse. Vous devez etre administrateur.')
    }
  } finally {
    loading.value = false
  }
}

const createCharts = () => {
  if (!charts.value) return

  const isDark = document.documentElement.classList.contains('dark')
  const gridColor = isDark ? 'rgba(63, 63, 70, 0.5)' : 'rgba(228, 228, 231, 0.5)'
  const textColor = isDark ? '#a1a1aa' : '#71717a'

  if (postsChart.value) {
    const ctx = postsChart.value.getContext('2d')
    if (postsChartInstance) postsChartInstance.destroy()

    const labels = charts.value.posts_per_day.map(item => {
      const date = new Date(item.date)
      return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
    })
    const data = charts.value.posts_per_day.map(item => item.count)

    postsChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Articles',
          data,
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
          fill: true,
          pointRadius: 3,
          pointBackgroundColor: '#10b981'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor } },
          x: { ticks: { color: textColor }, grid: { display: false } }
        }
      }
    })
  }

  if (usersChart.value) {
    const ctx = usersChart.value.getContext('2d')
    if (usersChartInstance) usersChartInstance.destroy()

    const labels = charts.value.users_per_day.map(item => {
      const date = new Date(item.date)
      return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
    })
    const data = charts.value.users_per_day.map(item => item.count)

    usersChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Inscriptions',
          data,
          backgroundColor: 'rgba(59, 130, 246, 0.8)',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor } },
          x: { ticks: { color: textColor }, grid: { display: false } }
        }
      }
    })
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' })
}

onMounted(() => fetchStats())
</script>

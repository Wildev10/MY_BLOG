<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
          <AdminSidebar />
        </div>

        <!-- Contenu principal -->
        <div class="lg:col-span-4">
          <!-- Header -->
          <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
              Tableau de bord
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">
              Vue d'ensemble de l'activité et des performances.
            </p>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="flex justify-center items-center py-32">
             <div class="w-10 h-10 rounded-full border-2 border-slate-200 border-t-slate-800 animate-spin"></div>
          </div>

          <div v-else class="space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              <!-- Utilisateurs -->
              <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Utilisateurs</span>
                  <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                  </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats?.users?.total || 0 }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-slate-500">
                  <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded">+{{ stats?.users?.new_this_month || 0 }}</span> ce mois
                </div>
              </div>

              <!-- Articles -->
              <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Articles</span>
                   <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                  </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats?.posts?.total || 0 }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-slate-500">
                  <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded">+{{ stats?.posts?.published_today || 0 }}</span> aujourd'hui
                </div>
              </div>

              <!-- Commentaires -->
              <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Commentaires</span>
                   <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                  </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats?.comments?.total || 0 }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-slate-500">
                  <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded">+{{ stats?.comments?.today || 0 }}</span> aujourd'hui
                </div>
              </div>

              <!-- Likes -->
              <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Likes</span>
                   <div class="p-2 bg-pink-50 dark:bg-pink-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                  </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-slate-900 dark:text-white">{{ stats?.likes?.total || 0 }}</span>
                </div>
                <div class="mt-2 text-xs font-medium text-slate-500">
                  <span class="text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded">+{{ stats?.likes?.today || 0 }}</span> aujourd'hui
                </div>
              </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">
                  Articles publiés (7 derniers jours)
                </h3>
                <div class="h-64">
                  <canvas ref="postsChart"></canvas>
                </div>
              </div>

              <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-6">
                  Nouvelles inscriptions (7 derniers jours)
                </h3>
                <div class="h-64">
                  <canvas ref="usersChart"></canvas>
                </div>
              </div>
            </div>

            <!-- Section Articles -->
            <div v-if="posts" class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Articles récents</h3>
                    <!-- Tabs simple -->
                    <div class="flex bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                        <button 
                            @click="activeTab = 'recent'"
                            :class="activeTab === 'recent' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-700'"
                            class="px-3 py-1 text-xs font-bold rounded-md transition-all"
                        >Récents</button>
                        <button 
                            @click="activeTab = 'popular'"
                            :class="activeTab === 'popular' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-700'"
                            class="px-3 py-1 text-xs font-bold rounded-md transition-all"
                        >Populaires</button>
                    </div>
                </div>

              <div class="divide-y divide-slate-100 dark:divide-slate-800">
                <template v-if="activeTab === 'recent'">
                  <div 
                    v-for="post in posts.recent" 
                    :key="post.id" 
                    @click="goToPost(post.id)"
                    class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer group"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-4">
                         <div class="h-10 w-10 rounded bg-slate-100 dark:bg-slate-800 flex-shrink-0 overflow-hidden">
                             <img v-if="post.image_url" :src="post.image_url" class="h-full w-full object-cover" />
                             <div v-else class="h-full w-full flex items-center justify-center text-slate-400">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                             </div>
                         </div>
                         <div>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors block">
                            {{ post.title }}
                            </span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-slate-500">{{ post.user?.name }}</span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span class="text-xs text-slate-400">{{ formatDate(post.created_at) }}</span>
                            </div>
                         </div>
                      </div>
                      
                      <div class="flex items-center gap-6">
                         <div class="flex items-center gap-1 text-xs font-medium text-slate-500">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                             --
                         </div>
                         <div class="flex items-center gap-1 text-xs font-medium text-slate-500">
                             <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                             {{ post.likes_count }}
                         </div>
                         <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                      </div>
                    </div>
                  </div>
                </template>

                <template v-if="activeTab === 'popular'">
                   <div 
                    v-for="post in posts.popular" 
                    :key="post.id" 
                    @click="goToPost(post.id)"
                    class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer group"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-4">
                         <div class="h-10 w-10 rounded bg-slate-100 dark:bg-slate-800 flex-shrink-0 overflow-hidden">
                             <img v-if="post.image_url" :src="post.image_url" class="h-full w-full object-cover" />
                             <div v-else class="h-full w-full flex items-center justify-center text-slate-400">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                             </div>
                         </div>
                         <div>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors block">
                            {{ post.title }}
                            </span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-slate-500">{{ post.user?.name }}</span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span class="text-xs text-slate-400">{{ formatDate(post.created_at) }}</span>
                            </div>
                         </div>
                      </div>
                      
                      <div class="flex items-center gap-6">
                         <div class="flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                             <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                             {{ post.likes_count }}
                         </div>
                      </div>
                    </div>
                  </div>
                </template>

                <!-- Message si pas d'articles -->
                <div v-if="(activeTab === 'recent' && (!posts.recent || posts.recent.length === 0)) ||
                           (activeTab === 'popular' && (!posts.popular || posts.popular.length === 0))"
                     class="p-8 text-center text-sm text-slate-400">
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
  const gridColor = isDark ? 'rgba(51, 65, 85, 0.3)' : 'rgba(203, 213, 225, 0.3)'
  const textColor = isDark ? '#94a3b8' : '#64748b'

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
          borderColor: '#0f172a', 
          backgroundColor: (context) => {
            const ctx = context.chart.ctx;
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(15, 23, 42, 0.2)');
            gradient.addColorStop(1, 'rgba(15, 23, 42, 0)');
            return gradient;
          },
          tension: 0.4,
          fill: true,
          pointRadius: 0,
          pointHoverRadius: 4,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        interaction: { intersect: false, mode: 'index' },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor, borderDash: [4, 4] } },
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
          backgroundColor: '#3b82f6',
          borderRadius: 4,
          barThickness: 20
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor, borderDash: [4, 4] } },
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

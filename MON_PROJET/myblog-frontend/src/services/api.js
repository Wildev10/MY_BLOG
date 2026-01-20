import axios from 'axios'

// URL de base de votre API Laravel
const API_URL = 'http://127.0.0.1:8000/api'

// Créer une instance Axios avec la config de base
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Intercepteur : Ajoute automatiquement le token à chaque requête
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// ========== AUTHENTIFICATION ==========

export const register = (name, email, password, password_confirmation) => {
  return api.post('/register', { name, email, password, password_confirmation })
}

export const login = (email, password) => {
  return api.post('/login', { email, password })
}

export const logout = () => {
  return api.post('/logout')
}

export const getCurrentUser = () => {
  return api.get('/user')
}

// ========== POSTS ==========

export const getPosts = (page = 1) => {
  return api.get(`/posts?page=${page}`)
}

export const getPost = (id) => {
  return api.get(`/posts/${id}`)
}

export const createPost = (formData) => {
  return api.post('/posts', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}


export const updatePost = (id, formData) => {
  // Pour Laravel, on doit utiliser POST avec _method=PUT pour les fichiers
  formData.append('_method', 'PUT')
  return api.post(`/posts/${id}`, formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}
export const deletePost = (id) => {
  return api.delete(`/posts/${id}`)
}

export const getMyPosts = () => {
  return api.get('/my-posts')
}


// ========== RECHERCHE ==========

export const searchPosts = (query) => {
  return api.get('/posts/search', { params: { q: query } })
}


// ========== CATÉGORIES ==========

export const getCategories = () => {
  return api.get('/categories')
}

export const createCategory = (name, color) => {
  return api.post('/categories', { name, color })
}

export const getCategoryPosts = (slug) => {
  return api.get(`/categories/${slug}/posts`)
}

// ========== LIKES ==========

export const toggleLike = (postId) => {
  return api.post(`/posts/${postId}/like`)
}

export const getLikes = (postId) => {
  return api.get(`/posts/${postId}/likes`)
}

// ========== COMMENTAIRES ==========

export const getComments = (postId) => {
  return api.get(`/posts/${postId}/comments`)
}

export const createComment = (postId, content) => {
  return api.post(`/posts/${postId}/comments`, { content })
}

export const updateComment = (commentId, content) => {
  return api.put(`/comments/${commentId}`, { content })
}

export const deleteComment = (commentId) => {
  return api.delete(`/comments/${commentId}`)
}

// ========== PROFIL UTILISATEUR ==========

/**
 * Récupérer le profil de l'utilisateur connecté
 */
export const getProfile = () => {
  return api.get('/profile')
}

/**
 * Mettre à jour le profil (avec support FormData pour avatar)
 */
export const updateProfile = (formData) => {
  // Si c'est un objet simple, on le convertit
  if (!(formData instanceof FormData)) {
    const data = formData
    formData = new FormData()
    Object.keys(data).forEach(key => {
      if (data[key] !== null && data[key] !== undefined) {
        formData.append(key, data[key])
      }
    })
  }
  
  formData.append('_method', 'PUT')
  return api.post('/profile', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

/**
 * Changer le mot de passe
 */
export const updatePassword = (data) => {
  return api.put('/profile/password', {
    current_password: data.current_password,
    password: data.password,
    password_confirmation: data.password_confirmation
  })
}

/**
 * Supprimer le compte
 */
export const deleteAccount = (password) => {
  return api.delete('/profile', {
    data: { password }
  })
}

/**
 * Upload d'avatar uniquement
 */
export const uploadAvatar = (file) => {
  const formData = new FormData()
  formData.append('avatar', file)
  return api.post('/profile/avatar', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

/**
 * Supprimer l'avatar
 */
export const deleteAvatar = () => {
  return api.delete('/profile/avatar')
}

/**
 * Récupérer le profil public d'un utilisateur
 */
export const getPublicProfile = (username) => {
  return api.get(`/users/${username}`)
}

/**
 * Récupérer les posts d'un utilisateur
 */
export const getUserPosts = (username, page = 1) => {
  return api.get(`/users/${username}/posts?page=${page}`)
}

// ========== NOTIFICATIONS ==========

export const getNotifications = (page = 1) => {
  return api.get(`/notifications?page=${page}`)
}

export const getUnreadCount = () => {
  return api.get('/notifications/unread-count')
}

export const markAsRead = (notificationId) => {
  return api.post(`/notifications/${notificationId}/mark-as-read`)
}

export const markAllAsRead = () => {
  return api.post('/notifications/mark-all-as-read')
}

export const deleteNotification = (notificationId) => {
  return api.delete(`/notifications/${notificationId}`)
}

// ========== ADMIN ==========

export const getAdminStats = () => {
  return api.get('/admin/stats')
}

export const getAdminUsers = (params = {}) => {
  return api.get('/admin/users', { params })
}

export const toggleUserAdmin = (userId) => {
  return api.put(`/admin/users/${userId}/toggle-admin`)
}

export const toggleUserBan = (userId) => {
  return api.put(`/admin/users/${userId}/toggle-ban`)
}

export const getAdminPosts = (params = {}) => {
  return api.get('/admin/posts', { params })
}

export const deleteAdminPost = (postId) => {
  return api.delete(`/admin/posts/${postId}`)
}

export const getAdminComments = (params = {}) => {
  return api.get('/admin/comments', { params })
}

export const deleteAdminComment = (commentId) => {
  return api.delete(`/admin/comments/${commentId}`)
}

export const getAdminCategoriesStats = () => {
  return api.get('/admin/categories/stats')
}

export const updateAdminCategory = (categoryId, data) => {
  return api.put(`/admin/categories/${categoryId}`, data)
}

export const deleteAdminCategory = (categoryId) => {
  return api.delete(`/admin/categories/${categoryId}`)
}

export default api
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

export const createPost = (title, content, category_id ) => {
  return api.post('/posts', { title, content, category_id })
}

export const updatePost = (id, title, content, category_id = null) => {
  return api.put(`/posts/${id}`, { title, content, category_id })
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


export default api
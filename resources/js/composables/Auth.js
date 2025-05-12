import { ref } from 'vue'
import { fetchJson } from '@/utils/fetchJson.js'

export function useAuth() {
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)
  const error = ref(null)
  const loading = ref(false)
  const isAuthenticated = ref(!!localStorage.getItem('token'))

  // Vérifier si l'utilisateur est connecté au chargement
  const checkAuth = async () => {
    if (!token.value) {
      isAuthenticated.value = false
      return
    }
    
    try {
      const { request } = fetchJson({
        url: 'profile',
        headers: {
          'Authorization': `Bearer ${token.value}`
        }
      })
      
      try {
        const data = await request
        user.value = data
        isAuthenticated.value = true
      } catch (err) {
        // Token invalide ou expiré
        logout()
      }
    } catch (err) {
      console.error('Erreur lors de la vérification de l\'authentification', err)
      logout()
    }
  }

  // Inscription
  const register = async (name, email, password, passwordConfirmation) => {
    loading.value = true
    error.value = null
    
    try {
      const { request } = fetchJson({
        url: 'register',
        method: 'POST',
        data: {
          name,
          email,
          password,
          password_confirmation: passwordConfirmation
        }
      })
      
      const data = await request
      user.value = data.user
      token.value = data.token
      localStorage.setItem('token', data.token)
      isAuthenticated.value = true
      
      return true
    } catch (err) {
      error.value = err.statusText || 'Erreur lors de l\'inscription'
      return false
    } finally {
      loading.value = false
    }
  }
  
  // Connexion
  const login = async (email, password) => {
    loading.value = true
    error.value = null

      console.log('Tentative de connexion avec:', { email });

    
    try {
      const { request } = fetchJson({
        url: 'login',
        method: 'POST',
        data: {
          email,
          password
        }
      })
      
      const data = await request
      user.value = data.user
      token.value = data.token
      localStorage.setItem('token', data.token)
      isAuthenticated.value = true
      
      return true
    } catch (err) {
      error.value = err.statusText || 'Identifiants incorrects'
      return false
    } finally {
      loading.value = false
    }
  }
  
  // Déconnexion
  const logout = async () => {
    if (token.value) {
      try {
        const { request } = fetchJson({
          url: 'logout',
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token.value}`
          }
        })
        
        await request
      } catch (err) {
        console.error('Erreur lors de la déconnexion', err)
      }
    }
    
    user.value = null
    token.value = null
    localStorage.removeItem('token')
    isAuthenticated.value = false
    
    return true
  }

  // Vérifier l'état de l'authentification au chargement du composable
  checkAuth()

  return {
    user,
    token,
    error,
    loading,
    isAuthenticated,
    register,
    login,
    logout,
    checkAuth
  }
}
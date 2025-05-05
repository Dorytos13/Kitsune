import { ref } from 'vue'

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
      const response = await fetch('/api/v1/profile', {
        headers: {
          'Authorization': `Bearer ${token.value}`
        }
      })
      
      if (response.ok) {
        const data = await response.json()
        user.value = data
        isAuthenticated.value = true
      } else {
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
      const response = await fetch('/api/v1/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          name,
          email,
          password,
          password_confirmation: passwordConfirmation
        })
      })
      
      const data = await response.json()
      
      if (!response.ok) {
        throw new Error(data.message || 'Erreur lors de l\'inscription')
      }
      
      user.value = data.user
      token.value = data.token
      localStorage.setItem('token', data.token)
      isAuthenticated.value = true
      
      return true
    } catch (err) {
      error.value = err.message
      return false
    } finally {
      loading.value = false
    }
  }
  
  // Connexion
  const login = async (email, password) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await fetch('/api/v1/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email,
          password
        })
      })
      
      const data = await response.json()
      
      if (!response.ok) {
        throw new Error(data.message || 'Identifiants incorrects')
      }
      
      user.value = data.user
      token.value = data.token
      localStorage.setItem('token', data.token)
      isAuthenticated.value = true
      
      return true
    } catch (err) {
      error.value = err.message
      return false
    } finally {
      loading.value = false
    }
  }
  
  // Déconnexion
  const logout = async () => {
    if (token.value) {
      try {
        await fetch('/api/v1/logout', {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token.value}`
          }
        })
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
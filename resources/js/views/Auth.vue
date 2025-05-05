<!-- filepath: c:\Users\dodir\Documents\DevProdMed\WebMobUI52-kitsune\resources\js\views\Auth.vue -->
<script setup>
import { ref, computed } from 'vue'
import { useAuth } from '@/composables/Auth.js'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const redirectPath = computed(() => route.query.redirect || '/stories')

const { register, login, error: authError, loading } = useAuth()

const mode = ref('login') // 'login' ou 'register'
const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const error = ref('')

const toggleMode = () => {
  mode.value = mode.value === 'login' ? 'register' : 'login'
  error.value = ''
}

const handleSubmit = async () => {
  error.value = ''
  let success = false
  
  if (mode.value === 'login') {
    success = await login(email.value, password.value)
  } else {
    if (password.value !== passwordConfirmation.value) {
      error.value = 'Les mots de passe ne correspondent pas'
      return
    }
    success = await register(
      name.value, 
      email.value, 
      password.value, 
      passwordConfirmation.value
    )
  }
  
  if (success) {
    router.push(redirectPath.value)
  } else if (authError.value) {
    error.value = authError.value
  }
}
</script>

<template>
  <div class="auth-page">
    <div class="auth-container">
      <h1 class="auth-title">{{ mode === 'login' ? 'Connexion' : 'Inscription' }}</h1>
      
      <div v-if="error" class="error-message">{{ error }}</div>
      
      <form @submit.prevent="handleSubmit" class="auth-form">
        <div v-if="mode === 'register'" class="form-group">
          <label for="name">Nom</label>
          <input 
            id="name" 
            v-model="name" 
            type="text" 
            required 
            placeholder="Votre nom"
          />
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            id="email" 
            v-model="email" 
            type="email" 
            required 
            placeholder="votre@email.com"
          />
        </div>
        
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input 
            id="password" 
            v-model="password" 
            type="password" 
            required 
            placeholder="Votre mot de passe"
          />
        </div>
        
        <div v-if="mode === 'register'" class="form-group">
          <label for="passwordConfirmation">Confirmer le mot de passe</label>
          <input 
            id="passwordConfirmation" 
            v-model="passwordConfirmation" 
            type="password" 
            required 
            placeholder="Confirmez votre mot de passe"
          />
        </div>
        
        <BaseButton 
          type="submit"
          variant="primary"
          :loading="loading"
          class="submit-button">
          {{ mode === 'login' ? 'Se connecter' : 'S\'inscrire' }}
        </BaseButton>
      </form>
      
      <div class="toggle-mode">
        {{ mode === 'login' ? 'Pas encore de compte ?' : 'Déjà un compte ?' }}
        <BaseButton 
          variant="secondary" 
          @click="toggleMode"
          class="toggle-button">
          {{ mode === 'login' ? 'S\'inscrire' : 'Se connecter' }}
        </BaseButton>
      </div>
    </div>
  </div>
</template>


<style scoped>
.auth-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #ffd1d1 0%, #fff9f9 100%);
  position: relative;
  overflow: hidden;
  padding: 2rem;
}

/* Motif japonais en arrière-plan */
.auth-page::before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  width: 100%;
  height: 100%;
  background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><path d="M10,10 L70,10 L70,70 L10,70 Z" stroke="%237c3046" stroke-width="0.5" fill="none" opacity="0.05"/><path d="M20,20 L60,20 L60,60 L20,60 Z" stroke="%237c3046" stroke-width="0.5" fill="none" opacity="0.05"/><circle cx="40" cy="40" r="5" stroke="%237c3046" stroke-width="0.5" fill="none" opacity="0.05"/></svg>');
  opacity: 0.4;
  z-index: 0;
}

.auth-container {
  width: 100%;
  max-width: 450px;
  padding: 2.5rem;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(124, 48, 70, 0.15);
  animation: slideIn 0.5s ease forwards;
  position: relative;
  z-index: 1;
  border: 1px solid rgba(124, 48, 70, 0.1);
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.auth-title {
  font-size: 2rem;
  text-align: center;
  margin-bottom: 2.5rem;
  color: #7c3046;
  position: relative;
  font-family: 'Playfair Display', serif;
  font-weight: 700;
}

.auth-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 2px;
  background-color: #7c3046;
}

.error-message {
  background-color: rgba(176, 0, 32, 0.1);
  color: #b00020;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
  border-left: 3px solid #b00020;
  font-family: 'Noto Sans', sans-serif;
}

.auth-form {
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #66464c;
  font-family: 'Noto Sans', sans-serif;
}

.form-group input {
  width: 100%;
  padding: 0.8rem 1rem;
  font-family: 'Noto Sans', sans-serif;
  font-size: 1rem;
  color: #66464c;
  background-color: #fff9f9;
  border: 1px solid #ffd1d1;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.form-group input:focus {
  outline: none;
  border-color: #7c3046;
  box-shadow: 0 0 0 3px rgba(124, 48, 70, 0.1);
}

.toggle-mode {
  text-align: center;
  font-size: 0.9rem;
  color: #66464c;
  margin-top: 1.5rem;
  font-family: 'Noto Sans', sans-serif;
}

/* Décoration Kitsune */
.auth-container::after {
  content: '';
  position: absolute;
  bottom: -20px;
  right: -20px;
  width: 100px;
  height: 100px;
  background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M40,30 C50,10 70,10 80,30 C90,50 80,80 50,80 C20,80 10,50 20,30 C30,10 50,10 60,30" stroke="%237c3046" stroke-width="1" fill="none" opacity="0.2"/><circle cx="35" cy="35" r="3" fill="%237c3046" opacity="0.2"/><circle cx="65" cy="35" r="3" fill="%237c3046" opacity="0.2"/><path d="M45,50 Q50,55 55,50" stroke="%237c3046" stroke-width="1" fill="none" opacity="0.2"/></svg>');
  background-size: contain;
  background-repeat: no-repeat;
  opacity: 0.2;
  z-index: -1;
}

/* Responsive */
@media (max-width: 576px) {
  .auth-container {
    padding: 1.5rem;
  }
  
  .auth-title {
    font-size: 1.7rem;
  }
}

/* Version sombre */
@media (prefers-color-scheme: dark) {
  .auth-page {
    background: linear-gradient(135deg, #3a2a30 0%, #2d2023 100%);
  }
  
  .auth-container {
    background-color: #3a2a30;
    border-color: rgba(255, 138, 138, 0.1);
  }
  
  .auth-title, 
  .form-group label {
    color: #ff8a8a;
  }
  
  .auth-title::after {
    background-color: #ff8a8a;
  }
  
  .form-group input {
    background-color: #2d2023;
    color: #ffd1d1;
    border-color: #66464c;
  }
  
  .toggle-mode {
    color: #ffd1d1;
  }
  
}
</style>
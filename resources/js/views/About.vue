<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/Auth.js'

const gameInfo = ref(null)
const loading = ref(true)
const error = ref(null)
const { isAuthenticated, token } = useAuth()
const router = useRouter()

onMounted(async () => {
  // Rediriger si non authentifié
  if (!isAuthenticated.value) {
    router.push('api/v1/login')
    return
  }
  
  try {
    const response = await fetch('api/v1/about', {
      headers: {
        'Authorization': `Bearer ${token.value}`
      }
    })
    
    if (!response.ok) {
      throw new Error('Vous devez être connecté pour accéder à ces informations')
    }
    
    gameInfo.value = await response.json()
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="game-info-page">
    <div v-if="loading" class="loading">
      Chargement des informations du jeu...
    </div>
    
    <div v-else-if="error" class="error">
      {{ error }}
    </div>
    
    <div v-else-if="gameInfo" class="info-container">
      <h1 class="title">{{ gameInfo.title }}</h1>
      <p class="description">{{ gameInfo.description }}</p>
      
      <div class="info-section">
        <h2>À propos</h2>
        <p>{{ gameInfo.about }}</p>
        <div class="metadata">
          <p><strong>Auteur:</strong> {{ gameInfo.author }}</p>
          <p><strong>Version:</strong> {{ gameInfo.version }}</p>
          <p><strong>Date de sortie:</strong> {{ gameInfo.releaseDate }}</p>
        </div>
      </div>
      
      <div class="info-section">
        <h2>Comment jouer</h2>
        <ul>
          <li v-for="(instruction, index) in gameInfo.howToPlay" :key="index">
            {{ instruction }}
          </li>
        </ul>
      </div>
      
      <BaseButton 
        variant="primary" 
        @click="router.push('/stories')"
        class="back-button">
        Retour aux histoires
      </BaseButton>
    </div>
  </div>
</template>

<style scoped>
.game-info-page {
  background: #22092c;
  color: white;
  padding: 2rem;
  min-height: 100vh;
  font-family: 'VT323', monospace;
}

.loading, .error {
  text-align: center;
  font-size: 1.5rem;
  margin: 2rem 0;
}

.error {
  color: #ff6b6b;
}

.info-container {
  max-width: 800px;
  margin: 0 auto;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  padding: 2rem;
  border-radius: 10px;
}

.title {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  text-align: center;
}

.description {
  font-size: 1.2rem;
  margin-bottom: 2rem;
  text-align: center;
  font-style: italic;
  line-height: 1.6;
}

.info-section {
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.info-section:last-child {
  border-bottom: none;
}

.info-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
  color: #bc6ff1;
}

.info-section p, .info-section li {
  line-height: 1.6;
  margin-bottom: 0.5rem;
}

.metadata {
  margin-top: 1rem;
  padding: 1rem;
  background: rgba(0, 0, 0, 0.2);
  border-radius: 5px;
}

@media (max-width: 768px) {
  .game-info-page {
    padding: 1rem;
  }
  
  .info-container {
    padding: 1rem;
  }
}
</style>
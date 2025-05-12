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
  background: var(--bg-light);
  color: var(--text-light);
  padding: 2rem;
  min-height: 100vh;
  font-family: var(--font-family-monospace);
}

.loading, .error {
  text-align: center;
  font-size: 1.5rem;
  margin: 2rem 0;
}

.error {
  color: var(--error-color);
}

.info-container {
  max-width: 800px;
  margin: 0 auto;
  background: var(--bg-dark);
  border: 1px solid var(--border-light);
  padding: 4rem;
  border-radius: var(--radius-xl);
}

.title {
  color: var(--accent);
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
  border-bottom: 1px solid var(--border-light);
}

.info-section:last-child {
  border-bottom: none;
}

.info-section h2 {
  font-size: 1.8rem;
  margin-bottom: 1rem;
  color: var(--primary-color);
}

.info-section p, .info-section li {
  line-height: 1.6;
  margin-bottom: 0.5rem;
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
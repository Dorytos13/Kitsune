<!-- filepath: c:\Users\dodir\Documents\DevProdMed\KITSUNE\Kitsune\resources\js\views\Stories.vue -->
<script setup>
import { useFetchJson } from '@/composables/useFetchJson'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/Auth.js'

const { data: stories, error, loading } = useFetchJson('/stories')
const router = useRouter()
const { isAuthenticated, user, logout } = useAuth()
defineProps(['story']);

function openStory(story) {
  if (!story.playable) return
  router.push(`/story/${story.id}`)
}

function startReading(story) {
  router.push(`/story/${story.id}`);
}

function showInfo() {
  router.push('/about')
}

function handleLogout() {
  logout();
  router.push('/');
}
</script>

<template>
  <div class="japan-container">
    <!-- Contenu à gauche -->
    <div class="content">
      <h2 class="welcome-text">DÉCOUVREZ</h2>
      <h1 class="japan-title">Les Histoires</h1>
      <p class="japan-description">
        Vous retrouverez ici toutes les histoires disponibles sur Kitsune. Pour l'instant, plongez dans notre toute première histoire intitulée "Le Voyage de Kitsune".
      </p>
      <div class="action-buttons">
        <BaseButton 
          variant="primary" 
          @click="showInfo"
          class="info-btn">
          En savoir plus
        </BaseButton>
        
        <!-- Bouton de déconnexion qui n'apparaît que si l'utilisateur est connecté -->
        <BaseButton 
          v-if="isAuthenticated" 
          variant="outline" 
          @click="handleLogout"
          class="logout-btn">
          Se déconnecter
        </BaseButton>
      </div>
    </div>
    
    <!-- Visualisation des histoires à droite -->
    <div class="stories-list">
      <div v-if="loading" class="loading-container">
        <div class="loading-spinner"></div>
        <p>Chargement des histoires...</p>
      </div>
      
      <div v-else-if="error" class="error-container">
        <p>{{ error }}</p>
      </div>
      
      <div v-else class="stories-scroll">
        <div
          v-for="(story, index) in stories"
          :key="story.id"
          class="story-card"
          :class="{ inactive: !story.playable }"
          :style="{ 'animation-delay': `${0.1 * (index + 1)}s` }"
          @click="openStory(story)"
        >
          <div class="story-tag">Disponible</div>
          <img :src="story.cover" class="story-cover" alt="Couverture de l'histoire" />
          <div class="story-content">
            <h2 class="story-title">{{ story.title }}</h2>
            <p class="story-summary">{{ story.summary }}</p>
            <BaseButton 
              variant="primary" 
              @click="startReading(story)" 
              class="start-button">
              Commencer la lecture
            </BaseButton>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
.japan-container {
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  overflow: hidden;
  position: relative;
  padding: 0 5%;
  background: linear-gradient(135deg, #fff5f5 0%, #ffd1d1 100%);  }

.content {
  width: 45%;
  z-index: 10;
  padding-right: var(--space-lg);
  padding-top: var(--space-lg);
  padding-bottom: var(--space-lg);
}

.welcome-text {
  font-size: 1.2rem;
  font-weight: 400;
  letter-spacing: 3px;
  color: var(--text-dark);
  margin-bottom: var(--space-xs);
  font-family: var(--font-main);
}

.japan-title {
  font-size: 4rem;
  font-weight: 700;
  color: var(--secondary-dark);
  margin-top: 0;
  margin-bottom: var(--space-lg);
  letter-spacing: 2px;
  font-family: var(--font-display);
}

.japan-description {
  font-size: 1.1rem;
  line-height: 1.6;
  color: var(--text-dark);
  margin-bottom: var(--space-xl);
  max-width: 500px;
  font-family: var(--font-jp);
}

.stories-list {
  position: relative;
  width: 55%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-buttons {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

/* Conteneur pour le scroll des histoires */
.stories-scroll {
  width: 100%;
  height: 75%;
  overflow-y: auto;
  padding: var(--space-lg);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: var(--space-lg);
  scrollbar-width: thin;
  scrollbar-color: var(--primary) rgba(124, 48, 70, 0.1);
  max-height: 80vh;
}

@keyframes fadeIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.story-content {
  padding: var(--space-lg);
  width: 65%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.story-title::after {
  content: '';
  position: absolute;
  bottom: -5px;
  left: 0;
  width: 30px;
  height: 2px;
  background-color: var(--primary);
  transition: width var(--transition-normal);
}

.start-button {
  margin-top: var(--space-md);
}

.error-container {
  text-align: center;
  font-size: 1.1rem;
  color: var(--error);
  padding: var(--space-lg);
  background-color: var(--error-light);
  border-radius: var(--radius-md);
  border-left: 4px solid var(--error);
  width: 80%;
  font-family: var(--font-main);
}

/* Responsive design */
@media (max-width: 992px) {
  .japan-container {
    flex-direction: column;
    padding-top: var(--space-xl);
    height: auto;
    min-height: 100vh;
  }
  
  .content {
    width: 90%;
    text-align: center;
    padding-right: 0;
    order: 1;
    margin-bottom: var(--space-lg);
  }
  
  .stories-list {
    width: 90%;
    height: auto;
    min-height: 60vh;
    order: 2;
  }
  
  .stories-scroll {
    max-height: 60vh;
    padding: var(--space-md);
  }
  
  
  .japan-description {
    max-width: 100%;
  }
  
  .japan-title {
    font-size: 3.5rem;
  }
}

@media (max-width: 576px) {
  .japan-title {
    font-size: 2.5rem;
  }
  
  .welcome-text {
    font-size: 1rem;
  }
}
</style>
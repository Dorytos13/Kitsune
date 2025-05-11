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

function startReading() {
  router.push(`/stories/${story.id}`);
}

function showInfo() {
  router.push('/about')
}
</script>

<template>
  <div class="japan-container">
    <!-- Contenu à gauche -->
    <div class="content">
      <h2 class="welcome-text">DÉCOUVREZ</h2>
      <h1 class="japan-title">Nos Histoires</h1>
      <p class="japan-description">
        Choisissez parmi nos récits japonais mettant en scène les légendaires kitsune. 
        Chaque histoire vous plongera dans un univers mystique où les frontières entre le monde des humains et celui des esprits s'estompent.
      </p>
      
      <div class="user-actions">
          <BaseButton 
            variant="primary" 
            @click="showInfo"
            class="info-btn">
            En savoir plus sur le jeu
          </BaseButton>
      </div>
    </div>
    
    <!-- Visualisation des histoires à droite -->
    <div class="torii-illustration">
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
          <div v-if="story.playable" class="story-tag">Disponible</div>
          <img :src="story.cover" class="story-cover" alt="Couverture de l'histoire" />
          <div class="story-content">
            <h2 class="story-title">{{ story.title }}</h2>
            <p v-if="story.playable" class="story-summary">{{ story.summary }}</p>
            <p v-else class="soon">En cours d'écriture...</p>
            <BaseButton 
              variant="primary" 
              @click="startReading" 
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

.user-actions {
  display: flex;
  gap: var(--space-md);
  flex-wrap: wrap;
  margin-top: var(--space-lg);
}

.torii-illustration {
  position: relative;
  width: 55%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Conteneur pour le scroll des histoires */
.stories-scroll {
  width: 100%;
  height: 75%;
  overflow-y: auto;
  padding: var(--space-lg);
  display: flex;
  flex-direction: column;
  gap: var(--space-lg);
  scrollbar-width: thin;
  scrollbar-color: var(--primary) rgba(124, 48, 70, 0.1);
  max-height: 80vh;
}

.stories-scroll::-webkit-scrollbar {
  width: 6px;
}

.stories-scroll::-webkit-scrollbar-track {
  background: rgba(124, 48, 70, 0.1);
  border-radius: var(--radius-lg);
}

.stories-scroll::-webkit-scrollbar-thumb {
  background-color: var(--primary);
  border-radius: var(--radius-lg);
}

.story-card {
  display: flex;
  background-color: var(--bg-light);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: 0 5px 15px var(--shadow);
  transition: transform var(--transition-normal), box-shadow var(--transition-normal);
  cursor: pointer;
  position: relative;
  animation: fadeIn 0.5s ease forwards;
  opacity: 0;
}

@keyframes fadeIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.story-card:hover:not(.inactive) {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px var(--shadow);
}

.story-card.inactive {
  opacity: 0.7;
  cursor: default;
  filter: grayscale(40%);
}

.story-tag {
  position: absolute;
  top: var(--space-md);
  right: var(--space-md);
  padding: 0.3rem 0.8rem;
  background-color: var(--secondary-dark);
  color: var(--text-light);
  font-size: 0.8rem;
  font-weight: 600;
  border-radius: var(--radius-md);
  z-index: 2;
  box-shadow: 0 2px 8px var(--shadow);
  font-family: var(--font-main);
}

.story-cover {
  width: 35%;
  height: auto;
  object-fit: cover;
  transition: transform var(--transition-slow);
}

.story-card:hover:not(.inactive) .story-cover {
  transform: scale(1.05);
}

.story-content {
  padding: var(--space-lg);
  width: 65%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.story-title {
  font-size: 1.4rem;
  margin-bottom: var(--space-md);
  color: var(--text-dark);
  font-family: var(--font-display);
  position: relative;
  font-weight: 600;
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

.story-card:hover:not(.inactive) .story-title::after {
  width: 50%;
}

.story-summary {
  color: var(--text-dark);
  font-size: 0.95rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.5;
  font-family: var(--font-jp);
}

.soon {
  font-style: italic;
  color: var(--primary);
  font-size: 0.9rem;
  position: relative;
  padding-left: 1.5rem;
  font-family: var(--font-jp);
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  gap: var(--space-lg);
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 3px solid rgba(124, 48, 70, 0.2);
  border-top-color: var(--primary);
  animation: spin 1s infinite linear;
}

.start-button {
  margin-top: var(--space-md);
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-container p {
  color: var(--text-dark);
  font-family: var(--font-main);
  font-size: 1.1rem;
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
  
  .torii-illustration {
    width: 90%;
    height: auto;
    min-height: 60vh;
    order: 2;
  }
  
  .stories-scroll {
    max-height: 60vh;
    padding: var(--space-md);
  }
  
  .story-card {
    flex-direction: column;
  }
  
  .story-cover {
    width: 100%;
    height: 180px;
  }
  
  .story-content {
    width: 100%;
  }
  
  .japan-description {
    max-width: 100%;
  }
  
  .japan-title {
    font-size: 3.5rem;
  }
  
  .user-actions {
    justify-content: center;
  }
}

@media (max-width: 576px) {
  .japan-title {
    font-size: 2.5rem;
  }
  
  .welcome-text {
    font-size: 1rem;
  }
  
  .user-actions {
    flex-direction: column;
    width: 100%;
  }
}
</style>
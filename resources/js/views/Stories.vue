<!-- filepath: c:\Users\dodir\Documents\DevProdMed\WebMobUI52-kitsune\resources\js\views\Stories.vue -->
<script setup>
import { useFetchJson } from '@/composables/useFetchJson'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/Auth.js'

const { data: stories, error, loading } = useFetchJson('/stories')
const router = useRouter()
const { isAuthenticated, user, logout } = useAuth()

function openStory(story) {
  if (!story.playable) return
  router.push(`/story/${story.id}`)
}

function navigateToLogin() {
  router.push('/login')
}

function navigateToGameInfo() {
  router.push('/game-info')
}

async function handleLogout() {
  await logout()
  router.push('/')
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
        <template v-if="isAuthenticated">
          <button class="info-btn" @click="navigateToGameInfo">
            ℹ️ Informations sur le jeu
          </button>
          <button class="logout-btn" @click="handleLogout">
            Déconnexion
          </button>
        </template>
        <button v-else class="read-more-btn" @click="navigateToLogin">
          Connexion
        </button>
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
          </div>
        </div>
      </div>
    </div>
    
    <!-- Nuages décoratifs -->
    <div class="cloud-decoration cloud-1"></div>
    <div class="cloud-decoration cloud-2"></div>
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
  background: linear-gradient(135deg, #fff5f5 0%, #ffd1d1 100%);
}

.content {
  width: 45%;
  z-index: 10;
  padding-right: 2rem;
  padding-top: 2rem;
  padding-bottom: 2rem;
}

.welcome-text {
  font-size: 1.2rem;
  font-weight: 400;
  letter-spacing: 3px;
  color: #b5555a;
  margin-bottom: 0.5rem;
  font-family: 'Noto Sans', sans-serif;
}

.japan-title {
  font-size: 4rem;
  font-weight: 700;
  color: #7c3046;
  margin-top: 0;
  margin-bottom: 1.5rem;
  letter-spacing: 2px;
  font-family: 'Playfair Display', serif;
}

.japan-description {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #66464c;
  margin-bottom: 2rem;
  max-width: 500px;
  font-family: 'Noto Serif JP', serif;
}

.user-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-top: 2rem;
}

.read-more-btn, .info-btn, .logout-btn {
  display: inline-block;
  padding: 0.8rem 2rem;
  border-radius: 30px;
  font-weight: 500;
  letter-spacing: 1px;
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  font-family: 'Noto Sans', sans-serif;
}

.read-more-btn {
  background-color: #7c3046;
  color: white;
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.3);
}

.read-more-btn:hover {
  background-color: #8e3751;
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(124, 48, 70, 0.4);
}

.info-btn {
  background-color: rgba(124, 48, 70, 0.1);
  color: #7c3046;
  border: 1px solid rgba(124, 48, 70, 0.3);
}

.info-btn:hover {
  background-color: rgba(124, 48, 70, 0.15);
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.1);
}

.logout-btn {
  background-color: transparent;
  border: 1px solid #7c3046;
  color: #7c3046;
}

.logout-btn:hover {
  background-color: rgba(124, 48, 70, 0.05);
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.1);
}

.torii-illustration {
  position: relative;
  width: 55%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Nuages stylisés */
.cloud-decoration {
  position: absolute;
  background: radial-gradient(#ffffff, transparent 70%);
  border-radius: 50%;
  z-index: 1;
}

.cloud-1 {
  top: 15%;
  left: 60%;
  width: 250px;
  height: 100px;
  opacity: 0.6;
}

.cloud-2 {
  top: 25%;
  left: 75%;
  width: 150px;
  height: 70px;
  opacity: 0.4;
}

/* Conteneur pour le scroll des histoires */
.stories-scroll {
  width: 100%;
  height: 75%;
  overflow-y: auto;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 2rem;
  scrollbar-width: thin;
  scrollbar-color: #7c3046 rgba(124, 48, 70, 0.1);
  max-height: 80vh;
}

.stories-scroll::-webkit-scrollbar {
  width: 6px;
}

.stories-scroll::-webkit-scrollbar-track {
  background: rgba(124, 48, 70, 0.1);
  border-radius: 10px;
}

.stories-scroll::-webkit-scrollbar-thumb {
  background-color: #7c3046;
  border-radius: 10px;
}

.story-card {
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(124, 48, 70, 0.1);
  transition: all 0.3s ease;
  cursor: pointer;
  position: relative;
  animation: fadeIn 0.5s ease forwards;
  opacity: 0;
  transform: translateY(20px);
  display: flex;
  border: 1px solid rgba(124, 48, 70, 0.05);
  min-height: 200px;
}

@keyframes fadeIn {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.story-card:hover:not(.inactive) {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(124, 48, 70, 0.15);
}

.story-card.inactive {
  opacity: 0.7;
  cursor: default;
  filter: grayscale(40%);
}

.story-tag {
  position: absolute;
  top: 1rem;
  right: 1rem;
  padding: 0.3rem 0.8rem;
  background-color: #7c3046;
  color: white;
  font-size: 0.8rem;
  font-weight: 600;
  border-radius: 20px;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(124, 48, 70, 0.3);
  font-family: 'Noto Sans', sans-serif;
}

.story-cover {
  width: 35%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.story-card:hover:not(.inactive) .story-cover {
  transform: scale(1.05);
}

.story-content {
  padding: 1.5rem;
  width: 65%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.story-title {
  font-size: 1.4rem;
  margin-bottom: 1rem;
  color: #66464c;
  font-family: 'Playfair Display', serif;
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
  background-color: #7c3046;
  transition: width 0.3s ease;
}

.story-card:hover:not(.inactive) .story-title::after {
  width: 50%;
}

.story-summary {
  color: #66464c;
  font-size: 0.95rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.5;
  font-family: 'Noto Serif JP', serif;
}

.soon {
  font-style: italic;
  color: #b5555a;
  font-size: 0.9rem;
  position: relative;
  padding-left: 1.5rem;
  font-family: 'Noto Serif JP', serif;
}

.soon::before {
  content: '🦊';
  position: absolute;
  left: 0;
  opacity: 0.7;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  gap: 1.5rem;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 3px solid rgba(124, 48, 70, 0.2);
  border-top-color: #7c3046;
  animation: spin 1s infinite linear;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-container p {
  color: #66464c;
  font-family: 'Noto Sans', sans-serif;
  font-size: 1.1rem;
}

.error-container {
  text-align: center;
  font-size: 1.1rem;
  color: #b00020;
  padding: 2rem;
  background-color: rgba(176, 0, 32, 0.05);
  border-radius: 8px;
  border-left: 4px solid #b00020;
  width: 80%;
  font-family: 'Noto Sans', sans-serif;
}

/* Responsive design */
@media (max-width: 992px) {
  .japan-container {
    flex-direction: column;
    padding-top: 2rem;
    height: auto;
    min-height: 100vh;
  }
  
  .content {
    width: 90%;
    text-align: center;
    padding-right: 0;
    order: 1;
    margin-bottom: 2rem;
  }
  
  .torii-illustration {
    width: 90%;
    height: auto;
    min-height: 60vh;
    order: 2;
  }
  
  .stories-scroll {
    max-height: 60vh;
    padding: 1rem;
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
  
  .cloud-1, .cloud-2 {
    display: none;
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
  
  .read-more-btn, .info-btn, .logout-btn {
    width: 100%;
  }
}

/* Version sombre */
@media (prefers-color-scheme: dark) {
  .japan-container {
    background: linear-gradient(135deg, #2a1a1d 0%, #3a2a30 100%);
  }
  
  .story-card {
    background-color: #3a2a30;
  }
  
  .japan-title {
    color: #ff8a8a;
  }
  
  .welcome-text {
    color: #ff8a8a;
  }
  
  .japan-description, .story-title, .story-summary {
    color: #ffd1d1;
  }
  
  .logout-btn {
    border-color: #ff8a8a;
    color: #ff8a8a;
  }
  
  .info-btn {
    background-color: rgba(255, 138, 138, 0.1);
    color: #ff8a8a;
    border-color: rgba(255, 138, 138, 0.3);
  }
  
  .loading-container p {
    color: #ffd1d1;
  }
  
  .story-title::after {
    background-color: #ff8a8a;
  }
  
  .soon {
    color: #ff8a8a;
  }
}
</style>
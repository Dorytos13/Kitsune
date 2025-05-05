<script setup>
import { ref,watch, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useFetchJson } from '@/composables/useFetchJson'
import { useAuth } from '@/composables/Auth.js'

const route = useRoute()
const router = useRouter()
const storyId = route.params.id

// Clé pour le stockage local
const STORAGE_KEY = `story_${storyId}_progress`;

const { isAuthenticated } = useAuth()
const { data: story, error, loading } = useFetchJson(`/stories/${storyId}`)

// Gestion des chapitres et des choix
const currentChapter = ref(null)
const chapterHistory = ref([])

// Charger l'historique depuis le stockage local
function loadProgress() {
  try {
    const savedProgress = localStorage.getItem(STORAGE_KEY);
    if (savedProgress) {
      return JSON.parse(savedProgress);
    }
  } catch (e) {
    console.error('Erreur lors du chargement de la progression:', e);
  }
  return null;
}

// Sauvegarder l'historique dans le stockage local
function saveProgress() {
  try {
    const progress = {
      currentChapterId: currentChapter.value?.id,
      history: chapterHistory.value
    };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(progress));
  } catch (e) {
    console.error('Erreur lors de la sauvegarde de la progression:', e);
  }
}

// Démarrer l'histoire avec le premier chapitre quand les données sont chargées
onMounted(() => {
  const unwatch = watch(story, (newStory) => {
    if (newStory && newStory.chapters && newStory.chapters.length > 0) {
      // Récupérer la progression sauvegardée
      const savedProgress = loadProgress();
      let targetChapter;
      
      if (savedProgress && savedProgress.currentChapterId) {
        targetChapter = newStory.chapters.find(c => c.id === savedProgress.currentChapterId);
        
        // Restaurer également l'historique si disponible
        if (savedProgress.history && Array.isArray(savedProgress.history)) {
          chapterHistory.value = savedProgress.history;
        }
      }
      
      // Fallback au premier chapitre si nécessaire
      if (!targetChapter) {
        targetChapter = newStory.chapters.find(c => c.chapter_number === 1);
        chapterHistory.value = [];
      }
      
      if (targetChapter) {
        currentChapter.value = targetChapter;
        
        // Si l'historique est vide, ajouter le chapitre actuel
        if (chapterHistory.value.length === 0) {
          chapterHistory.value.push(targetChapter.id);
        }
      }
      
      unwatch();
    }
  }, { immediate: true });
});

// Fonction pour définir le chapitre courant
function setCurrentChapter(chapter) {
  currentChapter.value = chapter
  chapterHistory.value.push(chapter.id)
  saveProgress()
}

// Faire un choix et passer au chapitre suivant
function makeChoice(choice) {
  const nextChapterId = choice.next_chapter_id
  const nextChapter = story.value.chapters.find(chapter => chapter.id === nextChapterId)
  
  if (nextChapter) {
    setCurrentChapter(nextChapter)
    // Défiler vers le haut pour que l'utilisateur puisse voir le nouveau contenu
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Retourner à l'écran des histoires
function backToStories() {
  router.push('/stories')
}

// Recommencer l'histoire
function restartStory() {
  const firstChapter = story.value.chapters.find(c => c.chapter_number === 1);
  if (firstChapter) {
    chapterHistory.value = [firstChapter.id];
    currentChapter.value = firstChapter;
    window.scrollTo({ top: 0, behavior: 'smooth' });
    saveProgress();
  }
}

// Vérifier si c'est la fin de l'histoire (pas de choix disponibles)
const isEndOfStory = computed(() => {
  return currentChapter.value && 
         (!currentChapter.value.choices || currentChapter.value.choices.length === 0)
})

// Vérifier si on peut revenir en arrière
const canGoBack = computed(() => chapterHistory.value.length > 1)

function goToPreviousChapter() {
  if (canGoBack.value) {
    chapterHistory.value.pop();
    const previousChapterId = chapterHistory.value[chapterHistory.value.length - 1];
    const previousChapter = story.value.chapters.find(chapter => chapter.id === previousChapterId);
    
    if (previousChapter) {
      currentChapter.value = previousChapter;
      window.scrollTo({ top: 0, behavior: 'smooth' });
      saveProgress();
    }
  }
}
</script>

<template>
  <div class="japan-container story-view">
    <!-- Éléments décoratifs japonais -->
    <div class="cloud-decoration cloud-1"></div>
    <div class="cloud-decoration cloud-2"></div>
    <div class="cherry-branch"></div>
    
    <!-- États de chargement -->
    <div v-if="loading" class="loading-container">
      <div class="loading-spinner"></div>
      <p>Chargement de l'histoire...</p>
    </div>
    
    <div v-else-if="error" class="error-container">
      <h2>Une erreur est survenue</h2>
      <p>{{ error }}</p>
      <button @click="backToStories" class="btn-primary">
        Retour aux histoires
      </button>
    </div>
    
    <div v-else-if="story && currentChapter" class="story-content">
      <!-- En-tête de l'histoire -->
      <div class="story-header">
        <h1 class="story-title">{{ story.title }}</h1>
        <div class="navigation-actions">
          <button v-if="canGoBack" 
                  @click="goToPreviousChapter" 
                  class="btn-outline">
            Chapitre précédent
          </button>
          <button @click="backToStories" class="btn-outline">
            Retour aux histoires
          </button>
        </div>
      </div>
      
      <!-- Contenu principal de l'histoire -->
      <div class="chapter-container">
        <!-- Image liée au chapitre si disponible -->
        <div class="chapter-image-container">
          <transition name="fade">
            <img :src="story.cover" 
                 :alt="story.title" 
                 class="chapter-image" />
          </transition>
        </div>
        
        <div class="chapter-content">
          <!-- Contenu du chapitre -->
          <p class="chapter-text">{{ currentChapter.content }}</p>
          
          <!-- Les choix à faire -->
          <div v-if="currentChapter.choices && currentChapter.choices.length > 0" 
               class="choices-container">
            <h2 class="choices-heading">Que décidez-vous ?</h2>
            <transition-group name="choice-appear" tag="div" class="choices-list">
              <button v-for="choice in currentChapter.choices" 
                      :key="choice.id"
                      @click="makeChoice(choice)" 
                      class="choice-button">
                {{ choice.text }}
              </button>
            </transition-group>
          </div>
          
          <!-- Fin de l'histoire -->
          <div v-if="isEndOfStory" class="story-end">
            <p class="end-message">Fin de l'histoire</p>
            <p class="author-note">Histoire écrite par {{ story.author }}</p>
            <div class="end-actions">
              <button @click="restartStory" class="btn-primary">
                Recommencer l'aventure
              </button>
              <button @click="backToStories" class="btn-outline">
                Découvrir d'autres histoires
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.japan-container.story-view {
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  overflow-x: hidden;
  position: relative;
  padding: 3rem 5% 5rem;
  background: linear-gradient(135deg, #fff5f5 0%, #ffd1d1 100%);
}

/* Décorations japonaises */
.cloud-decoration {
  position: absolute;
  background: radial-gradient(#ffffff, transparent 70%);
  border-radius: 50%;
  z-index: 1;
  pointer-events: none;
}

.cloud-1 {
  top: 10%;
  right: 15%;
  width: 250px;
  height: 100px;
  opacity: 0.6;
}

.cloud-2 {
  bottom: 30%;
  left: 10%;
  width: 180px;
  height: 80px;
  opacity: 0.4;
}

.cherry-branch {
  position: absolute;
  top: 5%;
  left: 5%;
  width: 150px;
  height: 120px;
  background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 120"><path d="M10,10 C30,20 70,15 120,50" stroke="%237c3046" stroke-width="2" fill="none" opacity="0.3"/><circle cx="30" cy="15" r="4" fill="%23ff8a8a" opacity="0.6"/><circle cx="50" cy="17" r="5" fill="%23ff8a8a" opacity="0.6"/><circle cx="70" cy="20" r="4" fill="%23ff8a8a" opacity="0.6"/><circle cx="90" cy="30" r="5" fill="%23ff8a8a" opacity="0.6"/><circle cx="110" cy="45" r="4" fill="%23ff8a8a" opacity="0.6"/></svg>');
  background-size: contain;
  background-repeat: no-repeat;
  z-index: 0;
  opacity: 0.7;
}

/* Conteneurs principaux */
.story-content {
  width: 100%;
  max-width: 1000px;
  position: relative;
  z-index: 2;
}

.story-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.story-title {
  font-size: 2.8rem;
  font-weight: 700;
  color: #7c3046;
  margin: 0;
  font-family: 'Playfair Display', serif;
  position: relative;
}

.story-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 80px;
  height: 3px;
  background-color: #7c3046;
}

.navigation-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

/* Contenu du chapitre */
.chapter-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
  background-color: rgba(255, 255, 255, 0.7);
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(124, 48, 70, 0.1);
  overflow: hidden;
  border: 1px solid rgba(124, 48, 70, 0.05);
}

.chapter-image-container {
  height: 100%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chapter-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.chapter-image:hover {
  transform: scale(1.05);
}

.chapter-content {
  padding: 2.5rem;
  display: flex;
  flex-direction: column;
}

.chapter-text {
  font-size: 1.2rem;
  line-height: 1.7;
  color: #66464c;
  margin-bottom: 2.5rem;
  font-family: 'Noto Serif JP', serif;
}

/* Conteneur de choix */
.choices-container {
  margin-top: auto;
}

.choices-heading {
  font-size: 1.5rem;
  color: #7c3046;
  margin-bottom: 1.5rem;
  font-family: 'Playfair Display', serif;
  position: relative;
  display: inline-block;
}

.choices-heading::before {
  content: '⛩️';
  margin-right: 0.5rem;
  font-size: 1.2rem;
}

.choices-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.choice-button {
  padding: 1rem 1.5rem;
  background-color: rgba(255, 255, 255, 0.7);
  border: 1px solid #7c3046;
  border-radius: 8px;
  font-size: 1.1rem;
  color: #7c3046;
  text-align: left;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: 'Noto Sans', sans-serif;
  position: relative;
  overflow: hidden;
}

.choice-button::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 100%;
  background-color: rgba(124, 48, 70, 0.1);
  transition: width 0.3s ease;
  z-index: -1;
}

.choice-button:hover {
  transform: translateX(8px);
  background-color: rgba(255, 255, 255, 0.9);
  border-color: #8e3751;
  color: #8e3751;
}

.choice-button:hover::after {
  width: 100%;
}

/* Fin de l'histoire */
.story-end {
  margin-top: 2rem;
  padding: 2rem;
  background-color: rgba(255, 255, 255, 0.5);
  border-radius: 12px;
  text-align: center;
  border-left: 3px solid #7c3046;
}

.end-message {
  font-size: 1.8rem;
  color: #7c3046;
  margin-bottom: 0.5rem;
  font-family: 'Playfair Display', serif;
  font-weight: 600;
}

.author-note {
  font-style: italic;
  color: #b5555a;
  margin-bottom: 2rem;
}

.end-actions {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

/* États de chargement et d'erreur */
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 60vh;
  text-align: center;
  gap: 1.5rem;
}

.loading-spinner {
  width: 60px;
  height: 60px;
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
  font-size: 1.2rem;
}

.error-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 2rem;
  background-color: rgba(176, 0, 32, 0.05);
  border-radius: 10px;
  border-left: 4px solid #b00020;
  text-align: center;
}

.error-container h2 {
  color: #b00020;
  margin-bottom: 1rem;
  font-family: 'Playfair Display', serif;
}

.error-container p {
  color: #66464c;
  margin-bottom: 2rem;
  font-family: 'Noto Sans', sans-serif;
}

/* Boutons */
.btn-primary, .btn-outline {
  display: inline-block;
  padding: 0.8rem 2rem;
  border-radius: 30px;
  font-weight: 500;
  letter-spacing: 1px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  cursor: pointer;
  font-family: 'Noto Sans', sans-serif;
}

.btn-primary {
  background-color: #7c3046;
  color: white;
  border: none;
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.3);
}

.btn-primary:hover {
  background-color: #8e3751;
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(124, 48, 70, 0.4);
}

.btn-outline {
  background-color: transparent;
  color: #7c3046;
  border: 1px solid #7c3046;
}

.btn-outline:hover {
  background-color: rgba(124, 48, 70, 0.05);
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.1);
}

/* Animations */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.choice-appear-enter-active {
  transition: all 0.3s;
  transition-delay: calc(var(--i) * 0.1s);
}

.choice-appear-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

/* Responsive */
@media (max-width: 992px) {
  .chapter-container {
    grid-template-columns: 1fr;
  }
  
  .story-title {
    font-size: 2.2rem;
  }
  
  .chapter-image-container {
    height: 300px;
  }
  
  .navigation-actions {
    width: 100%;
    justify-content: center;
  }
  
  .cherry-branch {
    display: none;
  }
}

@media (max-width: 576px) {
  .story-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .chapter-text {
    font-size: 1.1rem;
  }
  
  .choice-button {
    padding: 0.8rem 1.2rem;
  }
  
  .story-title {
    font-size: 1.8rem;
  }
  
  .chapter-image-container {
    height: 200px;
  }
}

/* Mode sombre */
@media (prefers-color-scheme: dark) {
  .japan-container.story-view {
    background: linear-gradient(135deg, #2a1a1d 0%, #3a2a30 100%);
  }
  
  .chapter-container {
    background-color: rgba(58, 42, 48, 0.7);
    border-color: rgba(255, 138, 138, 0.1);
  }
  
  .story-title {
    color: #ff8a8a;
  }
  
  .story-title::after {
    background-color: #ff8a8a;
  }
  
  .chapter-text {
    color: #ffd1d1;
  }
  
  .choice-button {
    background-color: rgba(58, 42, 48, 0.8);
    border-color: #ff8a8a;
    color: #ff8a8a;
  }
  
  .choice-button:hover {
    background-color: rgba(58, 42, 48, 0.9);
    border-color: #ff9d9d;
    color: #ff9d9d;
  }
  
  .choices-heading {
    color: #ff8a8a;
  }
  
  .end-message {
    color: #ff8a8a;
  }
  
  .story-end {
    background-color: rgba(58, 42, 48, 0.5);
    border-left-color: #ff8a8a;
  }
  
  .loading-container p {
    color: #ffd1d1;
  }
  
  .btn-outline {
    color: #ff8a8a;
    border-color: #ff8a8a;
  }
  
  .btn-primary {
    background-color: #7c3046;
  }
  
  .btn-primary:hover {
    background-color: #8e3751;
  }
}
</style>
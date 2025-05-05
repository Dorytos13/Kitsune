import { ref, computed, watch } from 'vue'

export function useChapter(story, storyId) {
  // États
  const currentChapter = ref(null)
  const chapterHistory = ref([])
  
  // Clé pour le stockage local
  const STORAGE_KEY = `story_${storyId}_progress`
  
  // Charger l'historique depuis le stockage local
  function loadProgress() {
    try {
      const savedProgress = localStorage.getItem(STORAGE_KEY)
      if (savedProgress) {
        return JSON.parse(savedProgress)
      }
    } catch (e) {
      console.error('Erreur lors du chargement de la progression:', e)
    }
    return null
  }
  
  // Sauvegarder l'historique dans le stockage local
  function saveProgress() {
    try {
      const progress = {
        currentChapterId: currentChapter.value?.id,
        history: chapterHistory.value
      }
      localStorage.setItem(STORAGE_KEY, JSON.stringify(progress))
    } catch (e) {
      console.error('Erreur lors de la sauvegarde de la progression:', e)
    }
  }
  
  // Fonction pour initialiser le chapitre actuel
  function initChapter() {
    if (story.value && story.value.chapters && story.value.chapters.length > 0) {
      // Récupérer la progression sauvegardée
      const savedProgress = loadProgress()
      let targetChapter
      
      if (savedProgress && savedProgress.currentChapterId) {
        targetChapter = story.value.chapters.find(c => c.id === savedProgress.currentChapterId)
        
        // Restaurer également l'historique si disponible
        if (savedProgress.history && Array.isArray(savedProgress.history)) {
          chapterHistory.value = savedProgress.history
        }
      }
      
      // Fallback au premier chapitre si nécessaire
      if (!targetChapter) {
        targetChapter = story.value.chapters.find(c => c.chapter_number === 1)
        chapterHistory.value = []
      }
      
      if (targetChapter) {
        currentChapter.value = targetChapter
        
        // Si l'historique est vide, ajouter le chapitre actuel
        if (chapterHistory.value.length === 0) {
          chapterHistory.value.push(targetChapter.id)
        }
      }
    }
  }
  
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
  
  // Recommencer l'histoire
  function restartStory() {
    const firstChapter = story.value.chapters.find(c => c.chapter_number === 1)
    if (firstChapter) {
      chapterHistory.value = [firstChapter.id]
      currentChapter.value = firstChapter
      window.scrollTo({ top: 0, behavior: 'smooth' })
      saveProgress()
    }
  }
  
  // Revenir au chapitre précédent
  function goToPreviousChapter() {
    if (canGoBack.value) {
      chapterHistory.value.pop()
      const previousChapterId = chapterHistory.value[chapterHistory.value.length - 1]
      const previousChapter = story.value.chapters.find(chapter => chapter.id === previousChapterId)
      
      if (previousChapter) {
        currentChapter.value = previousChapter
        window.scrollTo({ top: 0, behavior: 'smooth' })
        saveProgress()
      }
    }
  }
  
  // Computed properties
  const isEndOfStory = computed(() => {
    return currentChapter.value && 
           (!currentChapter.value.choices || currentChapter.value.choices.length === 0)
  })
  
  const canGoBack = computed(() => chapterHistory.value.length > 1)
  
  // Surveiller les changements dans l'histoire pour initialiser le chapitre
  watch(() => story.value, (newStory) => {
    if (newStory) {
      initChapter()
    }
  }, { immediate: true })
  
  return {
    currentChapter,
    chapterHistory,
    setCurrentChapter,
    makeChoice,
    restartStory,
    goToPreviousChapter,
    isEndOfStory,
    canGoBack
  }
}
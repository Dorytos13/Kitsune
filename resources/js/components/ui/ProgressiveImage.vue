<template>
    <div class="image-container" :style="{ aspectRatio }">
      <img
        v-if="loading"
        :src="placeholder || ''"
        class="placeholder-image"
        alt="Chargement..."
      />
      <transition name="fade">
        <img
          v-if="!loading"
          :src="src"
          :alt="alt"
          class="main-image"
          @load="onLoad"
        />
      </transition>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps({
    src: { type: String, required: true },
    alt: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    aspectRatio: { type: String, default: '16/9' }
  })
  
  const loading = ref(true)
  
  function onLoad() {
    loading.value = false
  }
  </script>
  
  <style scoped>
  .image-container {
    position: relative;
    overflow: hidden;
    width: 100%;
  }
  
  .placeholder-image, .main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .placeholder-image {
    filter: blur(10px);
  }
  
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
  }
  
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>
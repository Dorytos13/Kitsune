<template>
    <!--
      Bouton personnalisable :
      - Applique des classes selon la variante (primary, outline, secondary)
      - Affiche un indicateur de chargement si loading est true
      - Désactive le bouton si disabled ou loading est true
      - Émet un événement 'click' lors du clic
    -->
    <button 
      :class="[
        'base-button', 
        `variant-${variant}`,
        { 'is-loading': loading }
      ]"
      :disabled="disabled || loading"
      @click="$emit('click')"
      :type="type"
    >
    <!-- Indicateur de chargement (spinner) -->
      <span v-if="loading" class="loading-indicator"></span>
      <!-- Contenu du bouton (slot) -->
      <slot></slot>
    </button>
  </template>
  
  <script setup>
  // Définition des props du composant :
  // - variant : style du bouton ('primary', 'outline', 'secondary')
  // - loading : affiche un spinner et désactive le bouton si true
  // - disabled : désactive le bouton si true
  // - type : type HTML du bouton ('button', 'submit', 'reset')
  defineProps({
    variant: {
      type: String,
      default: 'primary',
      validator: (value) => ['primary', 'outline', 'secondary'].includes(value)
    },
    loading: { 
      type: Boolean, 
      default: false 
    },
    disabled: { 
      type: Boolean, 
      default: false 
    },
    type: {
      type: String,
      default: 'button',
      validator: (value) => ['button', 'submit', 'reset'].includes(value)
    }
  })

  // Déclaration de l'événement 'click' émis par le bouton
  defineEmits(['click'])
  </script>
  
  <style scoped>
  /* Style de base du bouton */
  .base-button {
    display: inline-block;
    padding: 0.8rem 2rem;
    border-radius: 30px;
    font-weight: 500;
    letter-spacing: 1px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    cursor: pointer;
    font-family: 'Noto Sans', sans-serif;
    position: relative;
  }
  
  /* Style pour le spinner de chargement */
  .is-loading {
    color: transparent !important;
  }
  
  .loading-indicator {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin-top: -10px;
    margin-left: -10px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    animation: spin 1s infinite linear;
  }
  
  /* Variantes de boutons */
  .variant-primary {
    background-color: #7c3046;
    color: white;
    border: none;
    box-shadow: 0 4px 15px rgba(124, 48, 70, 0.3);
  }
  
  .variant-primary:hover:not(:disabled) {
    background-color: #8e3751;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(124, 48, 70, 0.4);
  }
  
  .variant-outline {
    background-color: transparent;
    color: #7c3046;
    border: 1px solid #7c3046;
  }
  
  .variant-outline:hover:not(:disabled) {
    background-color: rgba(124, 48, 70, 0.05);
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(124, 48, 70, 0.1);
  }
  
  .variant-secondary {
    background-color: #f8f0f2;
    color: #7c3046;
    border: 1px solid #f8f0f2;
  }
  
  .variant-secondary:hover:not(:disabled) {
    background-color: #fff;
    border-color: #7c3046;
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(124, 48, 70, 0.1);
  }
  
  /* États désactivés */
  .base-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
  }
  
  /* Animation du spinner */
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  
  /* Support du mode sombre */
  @media (prefers-color-scheme: dark) {
    .variant-primary {
      background-color: #7c3046;
    }
    
    .variant-outline {
      color: #ff8a8a;
      border-color: #ff8a8a;
    }
    
    .variant-outline:hover:not(:disabled) {
      background-color: rgba(255, 138, 138, 0.1);
    }
    
    .variant-secondary {
      background-color: rgba(58, 42, 48, 0.8);
      color: #ff8a8a;
      border-color: transparent;
    }
  }
  </style>
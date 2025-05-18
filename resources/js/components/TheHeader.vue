<script setup>
import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuth } from '@/composables/Auth.js'

const router = useRouter()
const route = useRoute()
const { user, isAuthenticated, logout } = useAuth()

// Détermine si nous sommes sur la page d'accueil
const isHomePage = computed(() => route.path === '/')

// Fonction pour gérer la déconnexion
async function handleLogout() {
  await logout()
  router.push('/')
}
</script>

<template>
  <header :class="{ 'transparent': isHomePage }">
    <div class="header-content">
      <div class="logo" @click="router.push('/')">
        <h1>Kitsune</h1>
      </div>

      <nav>
        <ul>
          <li>
            <router-link to="/stories">Histoires</router-link>
          </li>
          <li v-if="isAuthenticated">
            <router-link to="/game-info">Infos du jeu</router-link>
          </li>
        </ul>
      </nav>

      <div class="user-actions">
        <template v-if="isAuthenticated">
          <span class="username" v-if="user">{{ user.name }}</span>
          <BaseButton 
            variant="outline"
            @click="handleLogout">
            Déconnexion
          </BaseButton>
        </template>
        <template v-else>
          <BaseButton 
            variant="primary"
            @click="router.push('/login')">
            Connexion
          </BaseButton>
        </template>
      </div>
    </div>
  </header>
</template>


<style scoped>
header {
  background-color: rgba(255, 245, 245, 0.95);
  box-shadow: 0 2px 15px rgba(124, 48, 70, 0.15);
  padding: 1rem 2rem;
  position: sticky;
  top: 0;
  z-index: 100;
  transition: all 0.3s ease;
}

header.transparent {
  background-color: transparent;
  box-shadow: none;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1400px;
  margin: 0 auto;
}

.logo {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.logo h1 {
  font-family: 'Playfair Display', serif;
  font-size: 1.8rem;
  color: #7c3046;
  transition: all 0.3s ease;
  position: relative;
  font-weight: 700;
  letter-spacing: 1px;
}

.logo h1::before {
  content: '🦊';
  font-size: 1.3rem;
  margin-right: 0.5rem;
  opacity: 0;
  transform: translateX(-10px);
  transition: all 0.3s ease;
  position: absolute;
  left: -25px;
}

.logo:hover h1::before {
  opacity: 1;
  transform: translateX(0);
}

nav ul {
  display: flex;
  list-style: none;
  gap: 2rem;
  margin: 0;
  padding: 0;
}

nav li {
  position: relative;
}

nav a {
  font-weight: 500;
  color: #66464c;
  padding: 0.5rem 0;
  position: relative;
  transition: all 0.3s ease;
  text-decoration: none;
  font-family: 'Noto Sans', sans-serif;
  font-size: 1rem;
  letter-spacing: 0.5px;
}

nav a:hover {
  color: #7c3046;
}

nav a::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #7c3046;
  transition: width 0.3s ease;
}

nav a:hover::after, 
nav a.router-link-active::after {
  width: 100%;
}

.user-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.username {
  font-weight: 500;
  color: #66464c;
  position: relative;
  padding-left: 25px;
  font-family: 'Noto Sans', sans-serif;
}

.username::before {
  content: '👤';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  font-size: 1rem;
}

/* Torii gate design pour le lien actif */
nav a.router-link-active::before {
  content: '';
  position: absolute;
  top: -5px;
  left: 50%;
  transform: translateX(-50%);
  width: 8px;
  height: 2px;
  background-color: #7c3046;
}

.btn-primary, .btn-outline {
  display: inline-block;
  padding: 0.6rem 1.5rem;
  border-radius: 30px;
  font-weight: 500;
  letter-spacing: 1px;
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  cursor: pointer;
  font-family: 'Noto Sans', sans-serif;
  border: none;
}

.btn-primary {
  background-color: #7c3046;
  color: white;
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.2);
}

.btn-primary:hover {
  background-color: #8e3751;
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(124, 48, 70, 0.3);
}

.btn-outline {
  background-color: transparent;
  border: 1px solid #7c3046;
  color: #7c3046;
}

.btn-outline:hover {
  background-color: rgba(124, 48, 70, 0.05);
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    padding: 0.5rem 0;
  }
  
  nav ul {
    gap: 1.5rem;
    margin: 0.8rem 0;
  }
  
  .user-actions {
    margin-top: 0.5rem;
    width: 100%;
    justify-content: center;
  }

  header {
    padding: 0.8rem 1rem;
  }
}

/* Version sombre */
@media (prefers-color-scheme: dark) {
  header:not(.transparent) {
    background-color: #2d2023;
  }
  
  nav a {
    color: #ffd1d1;
  }
  
  .logo h1, nav a:hover {
    color: #ff8a8a;
  }
  
  .username {
    color: #ffd1d1;
  }
  
  .btn-outline {
    border-color: #ff8a8a;
    color: #ff8a8a;
  }
  
  nav a::after, nav a.router-link-active::before {
    background-color: #ff8a8a;
  }
}
</style>
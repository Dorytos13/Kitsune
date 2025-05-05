<template>
  <div class="japan-container">
    <div class="content">
      <h2 class="welcome-text">BIENVENUE DANS</h2>
      <h1 class="japan-title">KITSUNE</h1>
      <p class="japan-description">
        Plongez dans l'univers magique des légendes japonaises, où les kitsune - renards mystiques aux pouvoirs surnaturels - vous guideront à travers des récits ancestraux dans un cadre visuel inspiré du Japon traditionnel.
      </p>
      <router-link to="/stories" class="read-more-btn">
        DÉCOUVRIR
      </router-link>
    </div>
    
    <div class="torii-illustration">
      <div class="torii-image-container">
        <img src="/images/covers/Tori.jpg" alt="Torii japonais" class="torii-image" />
      </div>
    </div>
  </div>
</template>

<script setup>
// rien à faire ici pour l'instant
</script>

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
  width: 50%;
  z-index: 10;
  padding-right: 2rem;
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

.read-more-btn {
  display: inline-block;
  background-color: #7c3046;
  color: white;
  padding: 0.8rem 2rem;
  border-radius: 30px;
  font-weight: 500;
  letter-spacing: 1px;
  text-decoration: none;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(124, 48, 70, 0.3);
}

.read-more-btn:hover {
  background-color: #8e3751;
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(124, 48, 70, 0.4);
}

.torii-illustration {
  position: relative;
  width: 50%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Nouveau conteneur pour l'image du torii */
.torii-image-container {
  position: relative;
  width: 100%;
  height: 65%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2;
}

.torii-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  filter: drop-shadow(0 10px 20px rgba(124, 48, 70, 0.2));
  transform: translateY(-20px);
  transition: transform 0.5s ease;
}

.torii-image:hover {
  transform: translateY(-30px);
}




/* Ajout d'un nuage stylisé */
.japan-container::before {
  content: '';
  position: absolute;
  top: 15%;
  left: 60%;
  width: 250px;
  height: 100px;
  background: radial-gradient(#ffffff, transparent 70%);
  border-radius: 50%;
  opacity: 0.6;
  z-index: 1;
}

.japan-container::after {
  content: '';
  position: absolute;
  top: 25%;
  left: 75%;
  width: 150px;
  height: 70px;
  background: radial-gradient(#ffffff, transparent 70%);
  border-radius: 50%;
  opacity: 0.4;
  z-index: 1;
}

/* Responsive design */
@media (max-width: 992px) {
  .japan-container {
    flex-direction: column;
    padding-top: 2rem;
  }
  
  .content {
    width: 90%;
    text-align: center;
    padding-right: 0;
    order: 2;
    margin-bottom: 3rem;
  }
  
  .torii-illustration {
    width: 90%;
    height: 40vh;
    min-height: 300px;
    order: 1;
    margin-bottom: 2rem;
  }
  
  .torii-image-container {
    height: 100%;
  }
  
  .japan-description {
    max-width: 100%;
  }
  
  .water {
    height: 80px;
  }
  
  .japan-title {
    font-size: 3.5rem;
  }
}

@media (max-width: 576px) {
  .torii-image {
    max-width: 90%;
  }
  
  .japan-title {
    font-size: 2.5rem;
  }
  
  .welcome-text {
    font-size: 1rem;
  }
}
</style>
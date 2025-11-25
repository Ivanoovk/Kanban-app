<template>
  <div class="nf-container">
    <h1 class="nf-title">404</h1>
    <p class="nf-subtitle">Oops! The page you're looking for doesn't exist.</p>
   
    <router-link to="/login" class="nf-btn">
      Go back home
    </router-link>
  </div>
</template>

<style scoped>
.nf-container {
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  background: #f5f6fa;
  text-align: center;
  padding: 20px;
}

.nf-title {
  font-size: 100px;
  font-weight: 800;
  color: #6366f1;
  margin: 0;
}

.nf-subtitle {
  font-size: 20px;
  color: #666;
  margin-top: -10px;
  margin-bottom: 30px;
}

.nf-btn {
  background: #6366f1;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  text-decoration: none;
  transition: background .2s ease;
}

.nf-btn:hover {
  background: #4f51d8;
}
</style>
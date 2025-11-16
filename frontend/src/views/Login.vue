<template>
  <div class="auth-container">
    <div class="auth-card">
      <h1 class="title">Welcome Back</h1>
      <p class="subtitle">Log in to your account</p>

      <form class="form">
        <input type="email" placeholder="Email" class="input" />
        <input type="password" placeholder="Password" class="input" />

        <button class="btn">Login</button>
      </form>

      <p class="switch">
        Don't have an account?
        <router-link to="/register">Register</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
@import '../assets/auth.css';
</style>
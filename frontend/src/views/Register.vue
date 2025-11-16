<template>
  <div class="auth-container">
    <div class="auth-card">
      <h1 class="title">Create Account</h1>
      <p class="subtitle">Join and start organizing</p>

      <form class="form">
        <input type="text" placeholder="Name" class="input" />
        <input type="email" placeholder="Email" class="input" />
        <input type="password" placeholder="Password" class="input" />

        <button class="btn">Register</button>
      </form>

      <p class="switch">
        Already have an account?
        <router-link to="/login">Login</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
@import '../assets/auth.css';
</style>
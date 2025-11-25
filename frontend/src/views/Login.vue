<script setup lang="ts">
import { ref } from "vue";
import { api } from "../api";
import { useRouter } from "vue-router";

const router = useRouter();

const email = ref("");
const password = ref("");
const error = ref("");

const login = async () => {
  try {
    const res = await api.post("/login", {
      email: email.value,
      password: password.value
    });

    localStorage.setItem("token", res.data.token);

    router.push("/boards");
  } catch (err: any) {
    error.value = err.response?.data?.message || "Login failed";
  }
};
</script>


<template>
  <div class="auth-container">
    <div class="auth-card">
      <h1 class="title">Welcome Back</h1>
      <p class="subtitle">Log in to your account</p>

      <form class="form" @submit.prevent="login">
      <input class="input" v-model="email" placeholder="Email" />
      <input class="input" v-model="password" type="password" placeholder="Password" />

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
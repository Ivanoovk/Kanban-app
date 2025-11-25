
<script setup lang="ts">
import { ref } from "vue";
import { api } from "../api";
import { useRouter } from "vue-router";

const router = useRouter();

const name = ref("");
const email = ref("");
const password = ref("");
const error = ref("");

const register = async () => {
  try {
    const res = await api.post("/register", {
      name: name.value,
      email: email.value,
      password: password.value
    });

    localStorage.setItem("token", res.data.token);

    router.push("/boards");
  } catch (err: any) {
    error.value = err.response?.data?.message || "Registration failed";
  }
};
</script>



<template>
  <div class="auth-container">
    <div class="auth-card">
      <h1 class="title">Create Account</h1>
      <p class="subtitle">Join and start organizing</p>

      <form class="form" @submit.prevent="register">
      <input class="input" v-model="name" placeholder="Name" />
      <input class="input" v-model="email" placeholder="Email" />
      <input class="input" v-model="password" type="password" placeholder="Password" />

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
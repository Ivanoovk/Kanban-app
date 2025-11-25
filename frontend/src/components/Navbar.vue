<template>
  <nav class="navbar">
    <div class="navbar-left">
      <router-link to="/boards" class="logo">
        <img src="/src/assets/icons/list.png" alt="Logo" class="logo-img" />
        <span>Kanban App</span>
      </router-link>
    </div>

    <div class="navbar-right">
      <span v-if="userName" class="welcome">Welcome, {{ userName }}</span>

      <button @click="logout" class="logout-btn">
        Logout
      </button>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { api } from "../api"
import { useRouter } from "vue-router"

const router = useRouter()
const userName = ref("Loading...")

onMounted(async () => {
  try {
    const res = await api.get("/user")
    userName.value = res.data.name
  } catch {
    router.push("/login")
  }
})

const logout = async () => {
  await api.post("/logout")
  localStorage.removeItem("token")
  router.push("/login")
}
</script>

<style scoped>
.navbar {
  height: 60px;
  background: #ffffff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  display: flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  cursor: pointer;
}

.logo-img {
  width: 28px;
  height: 28px;
  object-fit: contain;
}

.logo span {
  font-weight: 700;
  font-size: 20px;
  letter-spacing: 0.03em;
  color: #111827;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.welcome {
  font-size: 14px;
  color: #4b5563;
}

.logout-btn {
  padding: 8px 14px;
  border-radius: 999px;
  border: none;
  background: #ef4444;
  color: #fff;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s ease;
}

.logout-btn:hover {
  background: #dc2626;
}
</style>
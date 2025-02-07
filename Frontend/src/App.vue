<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useUserStore } from '@/stores/userStore.js'

let userStore = useUserStore()
</script>

<template>
  <header class="bg-gradient-to-r from-blue-600 to-blue-400 text-white p-6 shadow-lg">
    <div
      v-if="userStore.isAuthenticated"
      class="container mx-auto flex justify-between items-center"
    >
      <RouterLink to="/" class="text-2xl font-extrabold tracking-wide hover:underline">
        GeoQuizz
      </RouterLink>

      <nav class="flex space-x-4">
        <RouterLink to="/profile" class="text-lg hover:text-blue-200 transition"
          >Profile
        </RouterLink>
        <RouterLink to="/login">
          <button
            @click="userStore.logout"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full shadow-md transition"
          >
            Logout
          </button>
        </RouterLink>
      </nav>
    </div>
    <div v-else class="container mx-auto flex justify-between items-center">
      <RouterLink to="/" class="text-2xl font-extrabold tracking-wide hover:underline">
        GeoQuizz
      </RouterLink>

      <nav class="flex space-x-4">
        <RouterLink to="/login" class="text-lg hover:text-blue-200 transition">Login</RouterLink>
        <RouterLink
          to="/register"
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full shadow-md transition"
        >
          Register
        </RouterLink>
      </nav>
    </div>
  </header>

  <main class="py-8 bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4">
      <RouterView />
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-300 py-4 text-center">
    <p>&copy; 2025 GeoQuizz.</p>
    <p>DWM1 S5</p>
  </footer>
</template>

<style scoped>
body {
  font-family: 'Inter', sans-serif;
  background-color: #f3f4f6;
}
</style>

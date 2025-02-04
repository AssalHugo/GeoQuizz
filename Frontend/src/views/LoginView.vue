<script>
import { login } from '@/services/httpClient.js'
import { useUserStore } from '@/stores/userStore.js'

export default {
  data() {
    return {
      email: '',
      password: '',
      error: null,
      userStore: useUserStore(),
    }
  },
  methods: {
    handleLogin() {
      login(this.email, this.password)
        .then((response) => {
          this.userStore.setToken(response.token)
          this.$router.push('/')
        })
        .catch((error) => {
          this.error = error
        })
    },
  },
}
</script>
<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h1>
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label for="email" class="block text-gray-700 font-bold mb-1">Email</label>
          <input
            type="text"
            id="email"
            v-model="email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div>
          <label for="password" class="block text-gray-700 font-bold mb-1">Password</label>
          <input
            type="password"
            id="password"
            v-model="password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <button
          type="submit"
          class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Login
        </button>
        <div v-if="error" class="text-red-500 text-center mt-2">{{ error }}</div>
      </form>
      <router-link to="/register" class="block text-center text-blue-500 hover:underline mt-6">
        Not registered yet? Create an account.
      </router-link>
    </div>
  </div>
</template>

<style scoped>
.mt-12 {
  margin-top: 3rem;
}
</style>

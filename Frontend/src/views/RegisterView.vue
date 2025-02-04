<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>
      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label for="nickname" class="block text-gray-700 font-bold mb-2">Nickname</label>
          <input type="text" id="nickname" v-model="nickname" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>
        <div class="mb-4">
          <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
          <input type="email" id="email" v-model="email" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>
        <div class="mb-4">
          <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
          <input type="password" id="password" v-model="password" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>
        <div class="mb-4">
          <label for="confirmPassword" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
          <input type="password" id="confirmPassword" v-model="confirmPassword" required
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>
        <div class="mb-4">
          <button type="submit"
                  class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Register
          </button>
        </div>
        <div v-if="error" class="text-red-500 text-center">{{ error }}</div>
      </form>
      <router-link to="/login" class="text-blue-500 hover:underline"><p class="text-center">Login</p></router-link>
    </div>
  </div>
</template>

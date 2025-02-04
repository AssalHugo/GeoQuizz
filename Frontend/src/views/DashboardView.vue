<script>
import { useUserStore } from '@/stores/userStore.js'
import { getGamesUser } from '@/services/httpClient.js'

export default {
  data() {
    return {
      userStore: useUserStore(),
      games: [],
    }
  },

  mounted() {
    getGamesUser().then((games) => {
      this.games = games
    })
  },
}
</script>

<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div v-if="userStore.token" class="container mx-auto px-4">
      <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800">
        Tableau de bord de
        <router-link to="/profile" class="text-blue-600 hover:underline">
          {{ userStore.user.nickname }}
        </router-link>
      </h1>
      <div class="mb-4">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-gray-700">Parties en cours</h2>
          <button
            @click="createGame"
            class="w-40 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Créer une partie
          </button>
        </div>
        <ul class="space-y-4">
          <li
            v-for="game in games"
            :key="game.id"
            class="bg-gray-100 p-4 rounded-lg shadow-sm hover:shadow-md transition"
          >
            <div class="flex justify-between items-center">
              <div>
                <b class="block text-lg text-gray-800">Partie de {{ game.user }}</b>
                <p class="text-sm text-gray-600">Statut: {{ game.state }}</p>
              </div>
              <div class="flex space-x-2">
                <button
                  @click="joinGame(game)"
                  class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  Rejoindre
                </button>
                <button
                  @click="deleteGame(game.id)"
                  class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                  Supprimer
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped></style>

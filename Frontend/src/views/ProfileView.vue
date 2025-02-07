<script>
import { useUserStore } from '@/stores/userStore.js'
import { getGamesUser, getHighestScore } from '@/services/httpClient.js'
import { format } from 'date-fns'
export default {
  methods: { format },
  data() {
    return {
      games: [],
      highestScore: [],
      userStore: useUserStore()
    }
  },

  mounted() {
    const user = this.userStore.user;
    getGamesUser(user).then(
      (games) => {
        this.games = games.games;
      }
    )
    getHighestScore(user).then(
      (highestScore) => {
        this.highestScore = highestScore.score;
      }
    )
  }
}
</script>


<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800">Profil de l'utilisateur</h1>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">History of games</h2>
      <ul class="space-y-4">
        <li v-for="game in games" :key="game.id" class="bg-gray-100 p-4 rounded-lg shadow-sm">
          <p><strong>Partie ID:</strong> {{ game.id }}</p>
          <p><strong>Date:</strong> {{ format(new Date(game.startTime), 'dd/MM/yyyy HH:mm') }}</p>
          <p><strong>Score:</strong> {{ game.score }}</p>
        </li>
      </ul>
    </section>

    <section class="mb-8">
      <h2 class="text-2xl font-bold mb-4">High-scores by serie</h2>
      <ul class="space-y-4">
        <li v-for="score in highestScore" :key="score.serieId" class="bg-gray-100 p-4 rounded-lg shadow-sm">
          <p><strong>Serie:</strong> {{ score.serieId }}</p>
          <p><strong>High-score:</strong> {{ score.max_score }}</p>
        </li>
      </ul>
    </section>
  </div>
</template>

<style scoped>

</style>

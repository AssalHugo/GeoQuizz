<script>
import { createGame, joinGame } from '@/services/httpClient.js'
import { useUserStore } from '@/stores/userStore.js'

export default {
  props: {
    serie: {
      type: Object,
      required: true,
    },
  },
  methods: {
    joinSerie() {
      const userStore = useUserStore()
      createGame(userStore.user, this.serie.id).then((game) => {
        joinGame(game.gameId.id).then(() => {
          this.$router.push(`/game/${game.gameId.id}`)
        })
      })
    },
  },
}
</script>
<template>
  <div class="flex justify-between items-center">
    <div>
      <b class="block text-lg text-gray-800">Série : {{ serie.titre }}</b>
    </div>
    <div class="flex space-x-2">
      <button
        @click="joinSerie"
        class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Jouer à la série
      </button>
    </div>
  </div>
</template>
<style scoped></style>

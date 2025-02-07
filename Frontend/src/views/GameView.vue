<script>
import { getGameById, getCurrentPhoto, getSerieById } from '@/services/httpClient'
import MapComponent from '@/components/MapComponent.vue'
import PhotoComponent from '@/components/PhotoComponent.vue'
import ButtonsComponent from '@/components/ButtonsComponent.vue'

export default {
  components: {
    MapComponent,
    PhotoComponent,
    ButtonsComponent,
  },
  data() {
    return {
      game: null,
      serie: null,
      currentPhoto: null,
      error: null,
      loading: true,
      finished: false,
      markerLat: 0,
      markerLong: 0,
    }
  },
  methods: {
    changeMarkerCoord(lat, long) {
      this.markerLat = lat
      this.markerLong = long
    },
    async initializeGame() {
      try {
        this.loading = true
        const [game] = await Promise.all([
          getGameById(this.$route.params.id),
        ])
        this.game = game.game
        console.log(this.game)
        if (this.game.state == "IN_PROGRESS") {
          this.serie = await getSerieById(this.game.serieId)
          this.currentPhoto = await getCurrentPhoto(this.$route.params.id)
        } else if ( this.game.state == "FINISHED") {
          this.finished = true
        }
      } catch (error) {
        this.error = error.message
      } finally {
        this.loading = false
      }
    }
  },
  async mounted() {
    this.initializeGame();
  }
}
</script>

<template>
  <div v-if="!finished" class="h-screen relative bg-gray-900">
    <!-- Image principale -->
    <div v-if="currentPhoto" class="absolute inset-0">
      <PhotoComponent :current-photo="currentPhoto" />
    </div>

    <!-- Titre et boutons -->
    <div class="absolute top-0 left-0 right-0 z-20">
      <div class="container mx-auto px-6 py-6 flex justify-between items-start">
        <!-- Titre et score -->
        <div class="space-y-2">
          <div v-if="serie" class="bg-black/60 backdrop-blur-sm rounded-lg px-4 py-2">
            <h1 class="text-2xl font-bold text-white">{{ serie.data.titre }}</h1>
          </div>
          <div v-if="game" class="bg-black/60 backdrop-blur-sm rounded-lg">
            <ButtonsComponent :game="game" :markerLat="markerLat" :markerLong="markerLong" @initializeGame="initialize-game" />
          </div>
        </div>
      </div>
    </div>

    <!-- Carte interactive -->
    <div v-if="serie" class="absolute bottom-6 right-6 z-30">
      <div
        class="transform transition-all duration-300 ease-in-out hover:scale-150 hover:-translate-x-20 hover:-translate-y-20">
        <div class="bg-white/90 backdrop-blur rounded-xl shadow-xl">
          <div class="bg-gray-800 px-3 py-1.5 text-sm text-gray-200">Placez votre marqueur</div>
          <div class="w-96 h-64">
            <MapComponent :serie="serie" @change-marker-coord="changeMarkerCoord" />
          </div>
        </div>
      </div>
    </div>

    <!-- Loader -->
    <div v-if="loading" class="absolute inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50">
      <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V4a10 10 0 00-10 10h2zm2 0a6 6 0 016-6V4a8 8 0 00-8 8h2zm6 0a4 4 0 014-4V4a6 6 0 00-6 6h2zm4 0a2 2 0 012-2V4a4 4 0 00-4 4h2z" />
      </svg>
    </div>

    <!-- Erreur -->
    <div v-if="error"
      class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-red-500/90 backdrop-blur-sm text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-md">
      {{ error }}
    </div>
  </div>
  <div v-if="finished && game">
    <h1>Le jeu est fini {{ game.score }}</h1>
  </div>
</template>

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
      mapRef: null,
      validate: false,
      showMap: true,
    }
  },
  methods: {
    changeMarkerCoord(lat, long) {
      this.markerLat = lat
      this.markerLong = long
      console.log(this.markerLat, this.markerLong)
      console.log(this.currentPhoto)
    },
    changeValidate() {
      this.validate = !this.validate
    },
    toggleMap() {
      this.showMap = !this.showMap
    },
    async initializeGame() {
      try {
        this.loading = true
        // Récupérer le jeu
        const gameResponse = await getGameById(this.$route.params.id)
        this.game = gameResponse.game

        if (this.game.state === "IN_PROGRESS") {
          // Attendre que les deux requêtes soient terminées
          const [serieResponse, photoResponse] = await Promise.all([
            getSerieById(this.game.serieId),
            getCurrentPhoto(this.$route.params.id)
          ])

          this.serie = serieResponse
          this.currentPhoto = photoResponse

          this.$nextTick(() => {
            if (this.$refs.mapRef) {
              this.$refs.mapRef.setupMap();
            }
          })
          console.log(this.game)

        } else if (this.game.state === "FINISHED") {
          this.finished = true
          console.log('Game is finished')
        }
      } catch (error) {
        console.error('Error:', error)
        this.error = error.message
      } finally {
        this.loading = false
      }
    }
  },
  mounted() {
    this.initializeGame();
  },
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
            <ButtonsComponent :game="game" :markerLat="markerLat" :markerLong="markerLong" :validate="validate"
              @initialize-game="initializeGame" @change-validate="changeValidate" />
          </div>
        </div>
      </div>
    </div>

    <!-- Map toggle button -->
    <button @click="toggleMap"
      class="absolute bottom-6 right-6 z-40 bg-white text-black p-2 rounded-full shadow-lg hover:bg-gray-100/80 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path v-if="showMap" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
      </svg>
    </button>

    <!-- Carte interactive -->
    <div v-if="serie" v-show="showMap" class="absolute bottom-16 right-6 z-30 transition-all duration-300"
      :class="showMap ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
      <div :class="[
        'transform transition-all duration-300 ease-in-out',
        validate ? 'scale-175 -translate-x-1/2 -translate-y-1/4' : 'hover:scale-125 hover:-translate-x-1/8 hover:-translate-y-1/8'
      ]">
        <div class="bg-white/90 backdrop-blur rounded-xl shadow-xl">
          <div class="bg-gray-800 px-3 py-1.5 text-sm text-gray-200">
            {{ validate ? currentPhoto.adresse : 'Placez votre marqueur' }}
          </div>
          <div class="w-[320px] h-[240px] sm:w-[400px] sm:h-[300px]">
            <MapComponent ref="mapRef" :serie="serie" :validate="validate" @change-marker-coord="changeMarkerCoord" />
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

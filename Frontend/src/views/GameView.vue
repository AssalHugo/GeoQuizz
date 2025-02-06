<script>
import { getGameById, getCurrentPhoto, getSerieById } from '@/services/httpClient';
import MapComponent from '@/components/MapComponent.vue';
import PhotoComponent from '@/components/PhotoComponent.vue';
import ButtonsComponent from '@/components/ButtonsComponent.vue';

export default {
  components: {
    MapComponent,
    PhotoComponent,
    ButtonsComponent
  },
  data() {
    return {
      game: null,
      serie: null,
      currentPhoto: null,
      error: null,
      loading: true,
      markerLat: 0,
      markerLong: 0
    }
  },
  methods: {
    changeMarkerCoord(lat, long){
      this.markerLat = lat
      this.markerLong = long
    }
  },
  async mounted() {
    try {
      this.loading = true;
      const [game, photo] = await Promise.all([
        getGameById(this.$route.params.id),
        // getCurrentPhoto(this.$route.params.id)
      ]);
      this.game = game;
      this.serie = await getSerieById(game.serieId);
      // this.currentPhoto = photo;
    } catch (error) {
      this.error = error.message;
    } finally {
      this.loading = false;
    }
  }
}
</script>

<template>
  <div v-if="game" class="min-h-screen bg-gray-900 relative overflow">
    <ButtonsComponent :game="game" :markerLat="markerLat" :markerLong="markerLong"/>
  </div>

  <div class="min-h-screen bg-gray-900 relative overflow-hidden">
    <!-- Titre de la série en haut -->
    <div v-if="serie" class="absolute top-0 left-0 right-0 z-20 bg-gradient-to-b from-black/90 to-transparent">
      <div class="container mx-auto px-6 py-6">
        <h1 class="text-4xl font-bold text-white tracking-tight">
          {{ serie.data.titre }}
        </h1>
      </div>
    </div>

    <!-- Image principale -->
    <div v-if="currentPhoto" class="absolute inset-0">
      <PhotoComponent :current-photo="currentPhoto" />
    </div>

    <!-- Carte interactive -->
    <div v-if="serie"
         class="absolute bottom-4 right-4 z-30 transition-all duration-300 ease-in-out transform hover:scale-200 hover:translate-x-[-5%] hover:translate-y-[-5%] origin-bottom-right">
      <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
        <!-- Barre de style -->
        <div class="bg-gray-800 px-4 py-2">
        </div>
        <!-- Conteneur de la carte -->
        <div class="w-96 h-64">
          <MapComponent :serie="serie" @change-marker-coord="changeMarkerCoord" />
        </div>
      </div>
    </div>

    <!-- Loader -->
    <div v-if="loading" class="absolute inset-0 bg-gray-900 flex items-center justify-center">
      <svg class="animate-spin h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V4a10 10 0 00-10 10h2zm2 0a6 6 0 016-6V4a8 8 0 00-8 8h2zm6 0a4 4 0 014-4V4a6 6 0 00-6 6h2zm4 0a2 2 0 012-2V4a4 4 0 00-4 4h2z"></path>
      </svg>
    </div>

    <!-- Message d'erreur -->
    <div v-if="error"
         class="absolute bottom-4 left-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg max-w-2xl mx-auto z-50">
      {{ error }}
    </div>
  </div>
</template>

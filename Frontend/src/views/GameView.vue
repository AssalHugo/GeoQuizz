<script>
import { getGameById, getCurrentPhoto, getSerieById } from '@/services/httpClient';
import MapComponent from '@/components/MapComponent.vue';

export default {
  components: {
    MapComponent
  },
  data() {
    return {
      game: null,
      serie: null,
      currentPhoto: null,
      error: null,
      loading: true,
      markerLat: null,
      markerLong:null
    }
  },
  methods: {
    getImageUrl(name) {
      return new URL(`../assets/images/nancy/${name}`, import.meta.url).href
    },
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
    <div class="absolute inset-0">
      <img
        src="../assets/images/nancy/photo1.png"
        alt="Photo du lieu à deviner"
        class="w-full h-full object-cover"
      />
    </div>

    <!-- Overlay avec informations de la photo -->
    <div class="absolute top-32 left-0 right-0">
      <div class="container mx-auto px-6">
        <div v-if="currentPhoto"
             class="inline-block bg-black/50 backdrop-blur-sm rounded-lg px-6 py-3">
          <h2 class="text-xl font-semibold text-white">
            Photo {{ currentPhoto.photo }}
          </h2>
        </div>
      </div>
    </div>

    <!-- Carte interactive -->
    <div v-if="serie"
         class="absolute bottom-4 right-4 z-30 transition-all duration-300 ease-in-out transform hover:scale-225 hover:translate-x-[-5%] hover:translate-y-[-5%] origin-bottom-right">
      <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
        <!-- Barre de style -->
        <div v-on:change="markerLat && markerLong" class="bg-gray-800 px-4 py-2">
        </div>
        <!-- Conteneur de la carte -->
        <div class="w-96 h-64">
          <MapComponent :serie="serie" @change-marker-coord="changeMarkerCoord" />
        </div>
      </div>
    </div>

    <!-- Message d'erreur -->
    <div v-if="error"
         class="absolute bottom-4 left-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg max-w-2xl mx-auto z-50">
      {{ error }}
    </div>
  </div>
</template>

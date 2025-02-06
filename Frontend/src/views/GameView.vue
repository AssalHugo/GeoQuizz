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
      loading: true
    }
  },
  methods: {
    getImageUrl(name) {
      return new URL(`../assets/images/nancy/${name}`, import.meta.url).href
    }
  },
  async mounted() {
    try {
      this.loading = true;
      const [game, photo] = await Promise.all([
        getGameById(this.$route.params.id),
        getCurrentPhoto(this.$route.params.id)
      ]);
      this.game = game;
      this.serie = await getSerieById(game.serie_id);
      this.currentPhoto = photo;
    } catch (error) {
      this.error = error.message;
    } finally {
      this.loading = false;
    }
  }
}
</script>

<template>
  <div class="relative min-h-screen bg-gray-900">
    <!-- Image en plein écran -->
    <div v-if="currentPhoto" class="absolute inset-0">
      <img
        :src="getImageUrl(currentPhoto.photo)"
        alt="Photo du lieu à deviner"
        class="w-full h-full object-cover"
      />
    </div>

    <!-- Overlay sombre en haut -->
    <!-- <div class="absolute top-0 left-0 right-0 bg-gradient-to-b from-black/70 to-transparent h-32">
      <div class="container mx-auto px-6 py-4">
        <h1 class="text-3xl font-extrabold text-white mb-2">
          Partie de {{ game }}
        </h1>
        <h2 v-if="currentPhoto" class="text-xl font-semibold text-gray-200">
          Photo {{ currentPhoto.photo }}
        </h2>
      </div>
    </div> -->

    <!-- Carte en bas à droite -->
    <div class="absolute bottom-4 right-4 w-96 h-64 z-10">
      <MapComponent :center />
    </div>

    <!-- État de chargement -->
    <div v-if="!currentPhoto" class="absolute inset-0 flex items-center justify-center bg-gray-900">
      <div class="text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-white mb-4"></div>
        <p class="text-xl text-white">Chargement de la photo...</p>
      </div>
    </div>

    <!-- Message d'erreur -->
    <div v-if="error" class="absolute bottom-4 left-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg">
      {{ error }}
    </div>
  </div>
</template>

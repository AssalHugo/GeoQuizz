<script>
import { validateAnswer, nextPhoto } from '@/services/httpClient'

export default {
  props: ['game', 'markerLat', 'markerLong', 'validate'],
  emits: ['initialize-game', 'change-validate'],
  data() {
    return {
      score: 0,
      scoreAvant: this.game.score,
      error: null,
    }
  },
  methods: {
    validateAnswer() {
      validateAnswer(this.game.id, this.markerLat, this.markerLong)
        .then((response) => {
          this.score = response.score - this.scoreAvant;
          console.log(this.score);
          this.scoreAvant = response.score;
          console.log (this.scoreAvant);
          this.$emit('change-validate');
        })
        .catch((error) => {
          console.error(error);
        });
      },
    nextPhoto() {
      nextPhoto(this.game.id)
        .then(() => {
          this.$emit('change-validate');
          this.$emit('initialize-game');
          this.score = 0;
        })
        .catch((error) => {
          console.error(error);
        });
    }
  }
}
</script>


<template>
  <div class="flex flex-col gap-2">
    <!-- Score display -->
    <div class="bg-black/60 backdrop-blur-sm rounded-lg px-4 py-2">
      <p class="text-white">Score de cette manche: {{ score }} / 20</p>
    </div>

    <!-- Validate button -->
    <button v-if="!validate && game.currentPhotoIndex < game.photoIds.length"
      @click="validateAnswer"
      class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors"
    >
      Valider
    </button>
    <button v-if="validate && game.currentPhotoIndex < game.photoIds.length"
      @click="nextPhoto"
      class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors"
    >
    Photo Suivante
  </button>
  <button v-if="game.currentPhotoIndex >= game.photoIds.length"
      @click="showResult"
      class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors"
    >
    Voir le résultat
  </button>
  </div>
</template>

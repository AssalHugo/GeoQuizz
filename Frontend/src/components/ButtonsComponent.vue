<script>
import { validateAnswer, nextPhoto } from '@/services/httpClient'

export default {
  props: ['game', 'markerLat', 'markerLong', 'validate'],
  emits: ['initialize-game', 'change-validate'],
  data() {
    return {
      score: 0,
      clicked: false,
      scoreAvant: this.game.score,
      error: null,
    }
  },
  methods: {
    validateAnswer() {
      if (this.clicked) return;
      this.clicked = true;
      validateAnswer(this.game.id, this.markerLat, this.markerLong)
        .then((response) => {
          this.score = response.score - this.scoreAvant;
          this.scoreAvant = response.score;
          this.$emit('change-validate');
          this.clicked = false;
        })
        .catch((error) => {
          console.error(error);
        });
      },
    nextPhoto() {
      if (this.clicked) return;
      this.clicked = true;
      nextPhoto(this.game.id)
        .then(() => {
          this.$emit('change-validate');
          this.$emit('initialize-game');
          this.score = 0;
          this.clicked = false;
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
      <p class="text-white">Score de cette manche: {{ score }} / 40</p>
    </div>

    <!-- Validate button -->
    <button v-if="!validate && game.currentPhotoIndex < game.photoIds.length"
      @click="validateAnswer"
      class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors"
    >
      Valider
    </button>
    <button v-if="validate && game.currentPhotoIndex < game.photoIds.length - 1"
      @click="nextPhoto"
      class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors"
    >
    Photo Suivante ({{ game.currentPhotoIndex + 1 }}/{{ game.photoIds.length }})
  </button>
  <button v-if="validate && game.currentPhotoIndex >= game.photoIds.length - 1"
      @click="nextPhoto"
      class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors"
    >
    Voir le résultat
  </button>
  </div>
</template>

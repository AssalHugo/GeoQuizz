<script>
import { validateAnswer, nextPhoto } from '@/services/httpClient'

export default {
  props: ['game', 'markerLat', 'markerLong'],
  emits: ['initialize-game'],
  data() {
    return {
      score: this.game.score,
      validate: false,
      error: null,
    }
  },
  methods: {
    validateAnswer() {
      validateAnswer(this.game.id, this.markerLat, this.markerLong)
        .then((response) => {
          console.log(response);
          this.score = response.score;
          this.validate = true;
        })
        .catch((error) => {
          console.error(error);
        });
      },
    nextPhoto() {
      nextPhoto(this.game.id)
        .then(() => {
          this.validate = false;
          this.$emit('initialize-game');
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
      <p class="text-white">Score: {{ score }}</p>
    </div>

    <!-- Validate button -->
    <button v-if="!validate"
      @click="validateAnswer"
      class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors"
    >
      Valider
    </button>
    <button v-if="validate"
      @click="nextPhoto"
      class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors"
    >
    Photo Suivante
  </button>
  </div>
</template>

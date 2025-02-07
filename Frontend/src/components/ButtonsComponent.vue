<script>
import { validateAnswer } from '@/services/httpClient'

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
          this.score = this.score + response.score;
          this.validate = true;
        })
        .catch((error) => {
          console.error(error);
        });
        this.$emit('initialize-game');
      },
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
    <button
      @click="validateAnswer"
      class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors"
    >
      Valider
    </button>
  </div>
</template>

<script>
import { validateAnswer } from '@/services/httpClient';


export default {
  props: ['game', 'markerLat', 'markerLong'],
  data() {
    return {
      score: this.game.score,
      error: null,
    }
  },
  methods: {
    validateAnswer() {
      validateAnswer(this.game.id, this.markerLat, this.markerLong)
        .then((response) => {
          console.log(response);
          this.score = this.score + response.data.score;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  }
}
</script>


<template>
  <div class="min-h-screen bg-gray-900 relative overflow">
    <button @click="validateAnswer" class="absolute top-0 right-0 m-4 bg-blue-500 text-white px-4 py-2 rounded-lg">Valider</button>
  </div>
</template>

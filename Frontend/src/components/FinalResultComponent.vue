<script>
export default {
  props: ['score'],
  data() {
    return {
      scoreFinal: this.score,
    }
  },
  computed: {
    scoreMessage() {
      if (this.scoreFinal >= 35) return "Excellent!"
      if (this.scoreFinal >= 25) return "Bien joué!"
      if (this.scoreFinal >= 15) return "Pas mal!"
      return "Continue tes efforts!"
    }
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-black">
    <div class="max-w-md w-full p-8 animate-fade-in">
      <div class="text-center space-y-8">
        <!-- Title -->
        <h1 class="text-4xl font-bold text-white">
          Partie terminée !
        </h1>

        <!-- Score Card -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 transform hover:scale-105 transition-all duration-300">
          <div class="space-y-4">
            <!-- Score Message -->
            <p class="text-2xl font-bold text-white">
              {{ scoreMessage }}
            </p>

            <!-- Score Display -->
            <div class="text-6xl font-bold bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">
              {{ scoreFinal }}/400
            </div>
          </div>
        </div>

        <!-- Return Button -->
        <router-link
          to="/"
          class="inline-block px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-xl
                 hover:from-blue-600 hover:to-purple-600 transform hover:-translate-y-1 transition-all duration-300
                 shadow-lg hover:shadow-xl"
        >
          Retour au dashboard
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out forwards;
}
</style>

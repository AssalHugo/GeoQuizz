<script>
import { useUserStore } from '@/stores/userStore.js'
import { getSeries } from '@/services/httpClient.js'
import SerieItem from '@/components/SerieItem.vue'

export default {
  components: {
    SerieItem,
  },
  data() {
    return {
      userStore: useUserStore(),
      series: [],
    }
  },
  mounted() {
    getSeries().then((series) => {
      this.series = series
    })
  },
}
</script>

<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-100">
    <h1 class="text-3xl font-extrabold mb-6 text-center text-gray-800">
      Séries disponibles ({{ series.length }})
    </h1>
    <div class="mb-4">
      <ul class="space-y-4">
        <li
          v-for="serie in series"
          :key="serie.id"
          class="bg-gray-100 p-4 rounded-lg shadow-sm hover:shadow-md transition"
        >
          <SerieItem :serie="serie" />
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped></style>

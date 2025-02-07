<script>
import { useUserStore } from '@/stores/userStore.js'
import { getGamesUser } from '@/services/httpClient.js'

export default {
  data() {
    return {
      games: [],
      userStore: useUserStore()
    }
  },

  mounted() {
    this.games = getGamesUser(this.userStore.user);
  }
}
</script>

<template>


</template>

<style scoped>

</style>

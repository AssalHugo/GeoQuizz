<script>
const images = import.meta.glob('@/assets/images/**/*', { eager: true });

export default {
  props: ['current-photo'],
  computed: {
    imageUrl() {
      return images[`/src/assets/images/${this.currentPhoto.photo}`]?.default || '';
    }
  }
}
</script>

<template>
  <img
    v-if="imageUrl"
    :src="imageUrl"
    alt="Photo du lieu à deviner"
    class="w-full h-full object-cover"
  />
  <p v-else>Image introuvable</p>
</template>

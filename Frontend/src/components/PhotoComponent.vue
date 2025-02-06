<script>
export default {
  props: ['current-photo'],
  methods: {
    getImageUrl(name) {
      return new URL(`../assets/images/nancy/${name}`, import.meta.url).href
    },
  },
}
</script>

<template>
  <img
    :src="getImageUrl(currentPhoto.photo)"
    alt="Photo du lieu à deviner"
    class="w-full h-full object-cover"
  />
</template>

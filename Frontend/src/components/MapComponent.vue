<!-- MapComponent.vue -->
<script>
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { onMounted, onUnmounted, ref } from 'vue';

export default {
  name: 'MapComponent',
  props: ['serie'],
  emits: ['change-marker-coord'],
  setup(props, { emit }) {
    const mapContainer = ref(null);
    let map = null;
    let marker = null;

    const setupMap = () => {
      const center = [props.serie.data.latitude, props.serie.data.longitude];
      const zoom = 13;

      if (map) map.remove();

      map = L.map(mapContainer.value).setView(center, zoom);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);

      map.on('click', (event) => {
        if (marker) {
          map.removeLayer(marker);
        }
        marker = L.marker(event.latlng).addTo(map);
        emit('change-marker-coord', event.latlng.lat, event.latlng.lng);
      });
    };

    onMounted(() => {
      setupMap();
    });

    onUnmounted(() => {
      if (map) {
        map.remove();
      }
    });

    return {
      mapContainer,
      setupMap
    };
  },
}
</script>

<template>
  <div ref="mapContainer" class="w-full h-full rounded-lg shadow-lg"></div>
</template>

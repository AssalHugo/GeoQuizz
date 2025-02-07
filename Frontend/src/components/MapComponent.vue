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
    let center = [props.serie.data.latitude, props.serie.data.longitude];
    let zoom = 13;

    onMounted(() => {
      delete L.Icon.Default.prototype._getIconUrl;
      L.Icon.Default.mergeOptions({
        iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
        iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).href,
        shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
      });

      // Initialize map
      map = L.map(mapContainer.value).setView(center, zoom);

      // Add tile layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);

      // Add click handler
      map.on('click', (event) => {
        // Remove existing marker if any
        if (marker) {
          map.removeLayer(marker);
        }
        // Add new marker
        marker = L.marker(event.latlng).addTo(map);
        emit('change-marker-coord', event.latlng.lat, event.latlng.lng);
        console.log(event.latlng);
      });


    });

    onUnmounted(() => {
      if (map) {
        map.remove();
      }
    });

    return {
      mapContainer
    };
  },
}
</script>

<template>
  <div ref="mapContainer" class="w-full h-full rounded-lg shadow-lg"></div>
</template>

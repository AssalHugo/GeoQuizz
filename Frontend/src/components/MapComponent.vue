<script>
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { onMounted, onUnmounted, ref, watch } from 'vue';

export default {
  name: 'MapComponent',
  props: ['serie', 'validate', 'current-photo'],
  emits: ['change-marker-coord'],
  setup(props, { emit }) {
    const mapContainer = ref(null);
    const map = ref(null);
    const marker = ref(null);
    const correctMarker = ref(null);

    const setupMap = () => {
      const center = [props.serie.data.latitude, props.serie.data.longitude];
      const zoom = 13;

      if (map.value) map.value.remove();

      map.value = L.map(mapContainer.value).setView(center, zoom);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map.value);

      map.value.on('click', (event) => {
        if (marker.value) {
          map.value.removeLayer(marker.value);
        }
        marker.value = L.marker(event.latlng).addTo(map.value);
        emit('change-marker-coord', event.latlng.lat, event.latlng.lng);
      });
    };

    watch(() => props.validate, (newValue) => {
      if (newValue && props.currentPhoto) {
        const correctIcon = L.icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });

        correctMarker.value = L.marker(
          [props.currentPhoto.latitude, props.currentPhoto.longitude],
          { icon: correctIcon }
        ).addTo(map.value);
      } else if (!newValue && correctMarker.value) {
        map.value.removeLayer(correctMarker.value);
        correctMarker.value = null;
      }
    });

    onMounted(() => {
      setupMap();
    });

    onUnmounted(() => {
      if (map.value) {
        map.value.remove();
      }
    });

    return {
      mapContainer,
      setupMap
    };
  }
}
</script>

<template>
  <div ref="mapContainer" class="w-full h-full rounded-lg shadow-lg"></div>
</template>

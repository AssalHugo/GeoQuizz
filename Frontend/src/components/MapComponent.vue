<!-- MapComponent.vue -->
<script>
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import { onMounted, onUnmounted, ref } from 'vue';

export default {
  name: 'MapComponent',
  props: [{
    center: {
      type: Array,
      default: () => [48.692054, 6.184417]
    },
    zoom: {
      type: Number,
      default: 13
    }
  }],
  setup(props) {
    const mapContainer = ref(null);
    let map = null;

    onMounted(() => {
      // Fix pour l'icône de marqueur Leaflet
      delete L.Icon.Default.prototype._getIconUrl;
      L.Icon.Default.mergeOptions({
        iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
        iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).href,
        shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
      });

      // Initialisation de la carte
      map = L.map(mapContainer.value).setView(props.center, props.zoom);

      // Ajout du fond de carte OpenStreetMap
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);
    });

    onUnmounted(() => {
      if (map) {
        map.remove();
      }
    });

    return {
      mapContainer
    };
  }
}
</script>

<template>
  <div ref="mapContainer" class="w-full h-full rounded-lg shadow-lg"></div>
</template>

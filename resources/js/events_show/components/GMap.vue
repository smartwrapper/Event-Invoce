<template>
  <div>
    <gmap-map
      :center="center"
      :zoom="12"
      style="width:100%;  height: 400px;"
    >
    <GmapMarker ref="myMarker"
    :position="google && new google.maps.LatLng(center)" />
    </gmap-map>
  </div>
</template>


<script>
import {gmapApi} from 'vue2-google-maps'


export default {
    props: ['lat','lng'],
    data() {
        return {
            center: { lat: this.lat ? parseFloat(this.lat) : 27.1751448, lng: this.lng ? parseFloat(this.lng) : 78.0399535 },
            markers: [],
            places: [],
            currentPlace: null
        };
    },
    
    computed: {
      google: gmapApi,
      
    },

    methods: {
        // receives a place object via the autocomplete component
        setPlace(place) {
            this.currentPlace = place;
        },

    },
}
</script>

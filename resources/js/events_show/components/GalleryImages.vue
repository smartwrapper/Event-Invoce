<template>
<div>
    <gallery :images="images" :index="index" @close="index = null"></gallery>
    <div class="row">
        <div class="col-md-3" v-for="(image, imageIndex) in images" :key="imageIndex">
            <div class="lgx-gallery-single-slider"
                @click="index = imageIndex"
                :style="{ backgroundImage: 'url(' + image + ')', width: '100%', height: '280px',  }"
            ></div>
        </div>
    </div>
    
</div>
</template>

<script>
import VueGallery from 'vue-gallery';

export default {
    props: [
        'gimages'
    ],
    data: function () {
        return {
            images: [],
            index: null
        };
    },

    components: {
        'gallery': VueGallery
    },

    mounted() {
        // add url to images
        this.gimages.forEach(function(value, key) {
            this.images.push('/storage/'+value);
        }.bind(this));    
    }, 
}
</script> 
<template>
    <carousel 
        :autoplay="true"
        :autoplayTimeout="5000"
        :scrollPerPage="false"
        :perPage="1"
        :paginationEnabled="false"
    >
        <slide 
            v-for="(item, index) in banners"
            v-bind:item="item"
            v-bind:index="index"
            v-bind:key="index"
            :class="'lgx-item-common'"
        >
            <div class="slider-text-single">
                <figure>
                    <img :src="'/storage/'+item.image" alt="">
                    <figcaption>
                        <div class="lgx-container">
                            <div class="lgx-hover-link">
                                <div class="lgx-vertical">
                                    <div class="lgx-banner-info">
                                        <h3 class="subtitle lgx-delay lgx-fadeInDown">{{ item.subtitle }}</h3>
                                        <h2 class="title lgx-delay lgx-fadeInDown">{{ item.title }}</h2>

                                        <div class="action-area">
                                            <div class="lgx-video-area" v-if="demo_mode">
                                                <a class="lgx-btn lgx-btn-white" target="_blank" href="https://classiebit.com/eventmie"><i class="fas fa-cloud-download-alt"></i> Free Demo</a>
                                                
                                                <a class="lgx-btn lgx-btn-success" target="_blank" href="https://classiebit.com/eventmie-pro"><i class="fas fa-shopping-cart"></i> Purchase PRO </a>

                                                <a class="lgx-btn lgx-btn-white" target="_blank" href="https://eventmie-pro-docs.classiebit.com"><i class="fas fa-book"></i> Docs </a>
                                                
                                                <a class="lgx-btn lgx-btn-primary" target="_blank" href="https://eventmie-pro-docs.classiebit.com/docs/1.7/changelog/changes"><i class="fas fa-book"></i> See What's New v1.7</a>
                                            </div>

                                            <div class="lgx-video-area" v-else>
                                                <a v-if="item.button_url != null && item.button_title != null" class="lgx-btn lgx-btn-red" :href="item.button_url">{{ item.button_title }} &nbsp;&nbsp; <i class="fas fa-long-arrow-alt-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </figcaption>
                </figure>
            </div>
            
        </slide>
    </carousel>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';
Vue.prototype.base_url = window.base_url;

export default {

    components: {
        Carousel,
        Slide
    },
    props: [
        'banners',
        'is_logged',
        'is_customer',
        'is_organiser',
        'is_admin',
        'is_multi_vendor',
        'demo_mode',
        'check_session',
        's_host'
        
    ],

    
    data() {
        return {
            check : 0
        }
    },    

    methods: {
        // return route with event slug
        getRoute(name){
            return route(name);
        },

        verifyD(){
            this.check = this.check_session ? 1 : 0;
            
            if(this.check == 0)
            {
                axios.post('https://cblicense.classiebit.com/verifyd',{
                    domain : window.location.hostname,
                    s_host : this.s_host
                })
                .then(res => {
                    if(typeof res.data.status !== 'undefined' && res.data.status != 0)
                        this.checkSession();
                    else
                        window.location.href = base_url+"/404";
                    
                })
                .catch(error => {
                    
                });
            }
        },
        
        // check Session
        checkSession(){
            axios.post(route('eventmie.check_session'))
            .then(res => {
              
            }).catch(error => {
                
            });
        }
    },

    mounted() {
        this.verifyD();       
    }
}
</script>
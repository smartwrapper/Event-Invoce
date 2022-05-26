<template>
    <div class="row">
                    
        <div class="col-12 col-sm-6 col-lg-4" 
            v-match-heights="{
                el: ['h5.sub-title'],  // Array of selectors to fix
            }"
            v-for="(event, index) in events" 
            :key="index"
        >
            <div class="lgx-event">
                <a :href="eventSlug(event.slug)" >

                    <!-- simple events means without repetitive who Upcomming-->
                    <div class="lgx-event__tag" 
                        v-if="!event.repetitive && moment().format('YYYY-MM-DD') < 
                            userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')"
                    >
                        <span>{{countDays(moment().format("YYYY-MM-DD"), userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'))-1}} {{ trans('em.days_left') }} </span>
                        <span>{{ trans('em.upcoming') }}</span>
                    </div>

                    <!-- simple events means without repetitive who today-->
                    <div class="lgx-event__tag" 
                        v-if="!event.repetitive && moment().format('YYYY-MM-DD') == userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')"
                    >
                        <span>{{ trans('em.today') }}</span>
                        <span>{{ trans('em.event') }}</span>
                    </div>

                     <!-- simple events means without repetitive who ended-->
                    <div class="lgx-event__tag" v-if="!event.repetitive && moment().format('YYYY-MM-DD') > 
                            userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')"
                    >
                        <span>{{ trans('em.ended') }}</span>
                        <span>{{ trans('em.event') }}</span>
                    </div>

                    <!-- repetitive events who Upcoming  -->
                    <div class="lgx-event__tag" v-if="event.repetitive && moment().format('YYYY-MM-DD') < userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')"
                    >
                        <span>{{countDays(moment().format("YYYY-MM-DD"), userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'))-1}} {{ trans('em.days_left') }} </span>
                        <span >{{ trans('em.upcoming') }}</span>
                    </div>
                    
                    <!-- repetitive events who Started -->
                    <div class="lgx-event__tag" v-if="event.repetitive && moment().format('YYYY-MM-DD') >= userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')     && moment().format('YYYY-MM-DD') <= userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')"
                    >
                        <span>{{ changeDateFormat(moment(userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'))) }}</span>
                        <span >{{ trans('em.started') }}</span>
                    </div>

                     <!-- repetitive events who Ended -->
                    <div class="lgx-event__tag" v-if="event.repetitive && moment().format('YYYY-MM-DD') > userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')">
                        <span >{{ trans('em.event') }}</span>
                        <span>{{ trans('em.ended') }}</span>
                    </div>
                     
                    <!-- online event -->
                    <div class="lgx-event__online" v-if="event.online_location">
                        <span><i class="fas fa-signal"></i> {{ trans('em.online') }}</span>
                        <span>{{ trans('em.event') }}</span>
                    </div>

                    <div class="lgx-event__image">
                        <img :src="'/storage/'+event.thumbnail" alt="">
                    </div>

                    <div class="lgx-event__info">
                        <div class="lgx-event__featured" v-if="event.repetitive">
                            
                            <span v-if="event.repetitive_type == 1">{{ trans('em.repetitive_daily')  }}</span>
                            <span v-if="event.repetitive_type == 2">{{ trans('em.repetitive_weekly') }}</span>
                            <span v-if="event.repetitive_type == 3">{{ trans('em.repetitive_monthly') }}</span>
                        </div>

                        <div class="lgx-event__featured-left"
                            v-if="checkFreeTickets(event.tickets)"
                        >
                            <span>{{ trans('em.free') }}</span>
                        </div>

                        <div class="meta-wrapper">
                            
                            <span> {{changeDateFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), "YYYY-MM-DD")}}</span> 
                            
                            <span v-if="event.start_date != event.end_date">{{ changeDateFormat(userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), "YYYY-MM-DD")}} </span>
                            <span v-else>{{ changeTimeFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) )}} - {{ changeTimeFormat(userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) )}} </span>
                            <span>{{ '('+ showTimezone() +')'  }}</span>
                            <span>{{event.city}}</span>
                        </div>
                        
                        <h3 class="title">{{ event.title }}</h3>
                        <h5 class="sub-title" v-if="event.excerpt">{{ event.excerpt }}</h5>
                        <h5 class="sub-title text-primary">@{{ event.venue}}</h5>

                        
                    </div>

                    <div class="lgx-event__footer">
                        <div 
                            v-for="(ticket, index1) in event.tickets" 
                            :key="index1" 
                            v-if="index1 <= 1"
                        >
                            {{ticket.title}} : {{ (ticket.price <= 0) ? trans('em.free') : ticket.price+' '+currency }}
                        </div>
                    </div>

                    <div class="lgx-event__category">
                        <span>{{ event.category_name }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-12" v-if="not_found">
            <h4 class="heading text-center mt-30"><i class="fas fa-exclamation-triangle"></i> {{ trans('em.events_not_found') }}</h4>
        </div>
        
    </div>
</template>

<script>

import mixinsFilters from '../mixins.js';

export default {
    
    props: ['events', 'currency', 'date_format'],

    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            not_found: false,
        }
    },

    methods:{
        
        // check free tickets of events
        checkFreeTickets(event_tickets = []){
            let free = false;
            event_tickets.forEach(function(value, key) {
                if(parseFloat(value.price) <= parseFloat(0))
                {
                    free = true;
                }
            });    
            return free;
        },

        
        // return route with event slug
        eventSlug: function eventSlug(slug) {
            return route('eventmie.events_show', [slug]);
        }

  
    },

    watch: {
        events: function () {
            this.not_found = false;
            if(this.events.length <= 0)
                this.not_found = true;
        },
    },

}
</script>
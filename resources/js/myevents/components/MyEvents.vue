<template>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a class="lgx-btn lgx-btn-red btn btn-block" :href="createEvent()"><span><i class="fas fa-calendar-plus"></i> {{ trans('em.create_event') }}</span></a>  
            </div>
        </div>
    </div>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12 table-responsive table-mobile">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('em.event') }}</th>
                            <th>{{ trans('em.start_date') }}</th>
                            <th>{{ trans('em.end_date') }}</th>
                            <th>{{ trans('em.start_time') }}</th>
                            <th>{{ trans('em.end_time') }}</th>
                            <th>{{ trans('em.repetitive') }}</th>
                            <th>{{ trans('em.payment_frequency') }}</th>
                            <th>{{ trans('em.publish') }}</th>
                            <th>{{ trans('em.status') }}</th>
                            <th>{{ trans('em.edit') }}</th>
                            <th>{{ trans('em.export_attendees') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(event, index) in events" :key="index" >
                            <td :data-title="trans('em.event')"><a :href="eventSlug(event.slug)">{{ event.title }}</a></td>
                            <td :data-title="trans('em.start_date')">{{ changeDateFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), 'YYYY-MM-DD') }} {{ '('+ showTimezone() +')'  }}</td>
                            
                            
                            <td v-if="userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD') <= userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')" :data-title="trans('em.end_date')">
                                {{ changeDateFormat(userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), 'YYYY-MM-DD') }} {{ '('+ showTimezone() +')'  }}</td>

                            <td v-else :data-title="trans('em.end_date')">{{ changeDateFormat(userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD'), 'YYYY-MM-DD') }} {{ '('+ showTimezone() +')'  }}</td>
                            

                            <td :data-title="trans('em.start_time')">{{ userTimezone(event.start_date+' '+event.start_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) }} {{ '('+ showTimezone() +')'  }}</td>
                            <td :data-title="trans('em.end_time')">{{ userTimezone(event.end_date+' '+event.end_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) }} {{ '('+ showTimezone() +')'  }}</td>
                            <td :data-title="trans('em.repetitive')">{{ event.repetitive ? trans('em.yes') : trans('em.no') }}</td>
                            <td :data-title="trans('em.payment_frequency')">{{ event.merge_schedule ? trans('em.monthly_weekly') : trans('em.full_advance') }}</td>
                            <td :data-title="trans('em.publish')">{{ event.publish ? trans('em.published') : trans('em.unpublished') }}</td>
                            <td :data-title="trans('em.status')">{{ event.status ? trans('em.enabled') : trans('em.disabled') }}</td>
                            <td :data-title="trans('em.edit')">
                                <a class="lgx-btn lgx-btn-sm" :href="eventEdit(event.slug)"><i class="fas fa-edit"></i> {{ trans('em.edit') }}</a>
                            </td>
                            <td :data-title="trans('em.export_attendees')">
                                <a :class="{ 'disabled' : event.count_bookings < 1 }" class="btn lgx-btn lgx-btn-black lgx-btn-sm btn-block" :href="exportAttendies(event.slug, event.count_bookings)"><i class="fas fa-file-csv"></i> {{ trans('em.export_attendees') }} CSV ({{ event.count_bookings }})</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row" v-if="events.length > 0">
            <div class="col-md-12 text-center">
                <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'myevents'" @paginate="getMyEvents()"></pagination-component>
            </div>     
        </div>
    </div>
</div>       
</template>

<script>

import PaginationComponent from '../../common_components/Pagination'

import mixinsFilters from '../../mixins.js';


export default {
    props: [
        // pagination query string
        'page',
        'category',
        'date_format'
    ],

    components: {
        PaginationComponent,
    },
    
    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            events           : [],
            pagination: {
                'current_page': 1
            },
            moment           : moment,
        }
    },
    
    computed: {
        current_page() {
            // get page from route
            if(typeof this.page === "undefined")
                return 1;
            
            return this.page;
        },
    },
    methods: {
        
        // get all events
        getMyEvents() {
            axios.get(route('eventmie.myevents')+'?page='+this.current_page)
            .then(res => {
                
                this.events  = res.data.myevents.data;

                this.pagination = {
                    'total' : res.data.myevents.total,
                    'per_page' : res.data.myevents.per_page,
                    'current_page' : res.data.myevents.current_page,
                    'last_page' : res.data.myevents.last_page,
                    'from' : res.data.myevents.from,
                    'to' : res.data.myevents.to
                };
            })
            .catch(error => {
                
            });
        },

        // edit myevents
        eventEdit(event_id) {
            return route('eventmie.myevents_form', {id: event_id});
        },

        // create newevents
        createEvent() {
            return route('eventmie.myevents_form');
        },

        // return route with event slug
        eventSlug(slug){
            return route('eventmie.events_show',[slug]);
        },

        // ExportAttendies
        exportAttendies(event_slug = null, event_bookings = 0){
            if(event_slug != null && event_bookings > 0)
                return route('eventmie.export_attendees', [event_slug]);
            
        }
    },
    mounted() {
        this.getMyEvents();
    }
}
</script>
<style>

</style>
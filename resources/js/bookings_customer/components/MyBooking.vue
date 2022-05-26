<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 table-responsive table-mobile">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('em.order_id') }}</th>
                            <th>{{ trans('em.event') }}</th>
                            <th>{{ trans('em.ticket') }}</th>
                            <th>{{ trans('em.order_total') }} </th>
                            <th>{{ trans('em.booked_on') }} </th>
                            <th>{{ trans('em.payment') }} </th>
                            <th>{{ trans('em.checked_in') }}</th>
                            <th>{{ trans('em.status') }}</th>
                            <th>{{ trans('em.cancellation') }}</th>  
                            <th>{{ trans('em.expired') }}</th>  
                            <th>{{ trans('em.download') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <tr v-for="(booking, index) in bookings" :key="index" >
                            <td :data-title="trans('em.order_id')"><strong>#{{ booking.order_number }}</strong></td>
                            <td :data-title="trans('em.event')">
                                <a :href="eventSlug(booking.event_slug)">{{ booking.event_title+' ('+booking.event_category+')' }}</a>
                                <br><br>
                                <p class="text-bold text-small">{{ trans('em.timings') }}</p>
                                
                                <p class="text-small" v-if="booking.event_start_date != booking.event_end_date">{{ moment(userTimezone(booking.event_start_date+' '+booking.event_start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')).format(date_format.vue_date_format)+' - '+moment(userTimezone(booking.event_end_date+' '+booking.event_end_time,'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')).format(date_format.vue_date_format) }}</p>
                                <p class="text-small" v-else>{{ moment(userTimezone(booking.event_start_date+' '+booking.event_start_time,'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')).format(date_format.vue_date_format) }}</p>

                                <p class="text-small">{{ userTimezone(booking.event_start_date+' '+booking.event_start_time, 'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format)+' - '+userTimezone(booking.event_end_date+' '+booking.event_end_time,'YYYY-MM-DD HH:mm:ss').format(date_format.vue_time_format) }}</p>
                                
                                <p class="text-samll"> {{ '('+ showTimezone() +')'  }}</p>
                            </td>
                            <td :data-title="trans('em.ticket')">{{ booking.ticket_title }} <strong>{{ ' x '+booking.quantity }}</strong></td>
                            <td :data-title="trans('em.order_total')">{{ booking.net_price+' '+ currency }} </td>
                            <td :data-title="trans('em.booked_on')">{{ moment(userTimezone(booking.created_at, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD')).format(date_format.vue_date_format) }} {{ '('+ showTimezone() +')'  }}</td>
                            <td :data-title="trans('em.payment')" class="text-capitalize">
                                <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-if="booking.payment_type == 'offline'">
                                    {{ booking.payment_type }} 
                                    <hr class="small">
                                    <small class="text-small text-success" v-if="booking.is_paid">{{ trans('em.paid') }}</small>
                                    <small class="text-small text-danger" v-else>{{ trans('em.unpaid') }}</small>
                                </span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-success" v-else>{{ booking.payment_type }} <hr class="small"><small class="text-small">{{ booking.is_paid ? trans('em.paid') : trans('em.unpaid') }}</small></span>
                            </td>
                            <td :data-title="trans('em.checked_in')">
                                <span class="lgx-badge lgx-badge-small lgx-badge-success" v-if="booking.checked_in > 0">
                                    {{ trans('em.yes') }}
                                    <hr class="small">
                                    <small class="text-small text-white">{{ booking.checked_in +'/'+ booking.quantity }}</small>
                                </span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-else>{{ trans('em.no') }}</span>
                            </td>
                            <td :data-title="trans('em.status')">
                                <span class="lgx-badge lgx-badge-small lgx-badge-success" v-if="booking.status == 1">{{ trans('em.enabled') }}</span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-else>{{ trans('em.disabled') }}</span>
                            </td>
                            <td :data-title="trans('em.cancellation')" v-if="booking.booking_cancel == 0 && booking.status == 1 && booking.checked_in == 0">
                                <button type="button" class="lgx-btn lgx-btn-sm lgx-btn-danger" @click="bookingCancel(booking.id, booking.ticket_id, booking.event_id )" 
                                v-if="disable_booking_cancellation == null"
                                ><i class="fas fa-ban"></i> {{ trans('em.cancel') }}</button>
                                <p v-else>{{ trans('em.n/a') }}</p>
                            </td>
                            <td :data-title="trans('em.cancellation')" v-else>
                                <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-if="booking.booking_cancel == 0">{{ trans('em.disabled') }}</span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-warning" v-if="booking.booking_cancel == 1">{{ trans('em.pending') }}</span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-info" v-if="booking.booking_cancel == 2">{{ trans('em.approved') }}</span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-if="booking.booking_cancel == 3">{{ trans('em.refunded') }}</span>
                            </td>
                            
                            <!-- check booking expired or not -->
                            <td :data-title="trans('em.expired')" v-if="(moment(booking.event_end_date+' '+booking.event_end_time, 'YYYY-MM-DD HH:mm:ss').tz(Intl.DateTimeFormat().resolvedOptions().timeZone) <= moment().tz(Intl.DateTimeFormat().resolvedOptions().timeZone))">
                                <span class="lgx-badge lgx-badge-small lgx-badge-danger"> {{trans('em.yes')}} </span>
                            </td>

                            <td :data-title="trans('em.expired')" v-else>
                                <span class="lgx-badge lgx-badge-small lgx-badge-primary"> {{trans('em.no')}} </span>
                            </td>
                            <!-- check booking expired or not -->

                            <td :data-title="trans('em.download')">
                                <div v-if="hide_ticket_download == null" class="mb-2">
                                    <a v-if="booking.is_paid == 1 && booking.status == 1" class="lgx-btn lgx-btn-sm lgx-btn-success" :href="downloadURL(booking.id, booking.order_number)"><i class="fas fa-download"></i> {{trans('em.ticket')}}</a>
                                    <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-else>
                                        <small v-if="booking.is_paid == 0 && booking.status == 1" class="text-small text-danger">{{ trans('em.unpaid') }}</small>
                                        <small v-else class="text-small">{{ trans('em.disabled') }}</small>
                                    </span>
                                </div>

                                <div v-if="hide_google_calendar == null" class="mb-2">
                                    <create-google-event :booking="booking" :date_format="date_format"></create-google-event>
                                </div>

                                <div v-if="booking.online_location != null && booking.is_paid == 1 && booking.status == 1"> 
                                    <button type="button" class="lgx-btn lgx-btn-sm" @click="booking_id = booking.id"><i class="fas fa-tv"></i> {{ trans('em.online') +' '+ trans('em.event') }}</button>
                                    <online-event  v-if="booking_id == booking.id" :online_location="booking.online_location" :booking_id="booking.id" ></online-event>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="bookings.length <= 0">
                            <td colspan="10" class="text-center">{{ trans('em.no_bookings') }}</td>
                        </tr>
                
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" v-if="bookings.length > 0">
            <div class="col-md-12 text-center">
                <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'/mybookings'" @paginate="getMyBookings()">
                </pagination-component>
            </div>
        </div>
    </div>
</template>


<script>

import PaginationComponent from '../../common_components/Pagination'
import mixinsFilters from '../../mixins.js';
import OnlineEvent from './OnlineEvent.vue';
import CreateGoogleEvent from './CreateGoogleEvent.vue';

export default {
    
    mixins:[
        mixinsFilters
    ],

    props: [
        // pagination query string
        'page',
        'is_success',
        'date_format',
        'disable_booking_cancellation',
        'hide_ticket_download',
        'hide_google_calendar',
        
    ],
    
    components: {
        PaginationComponent,
        OnlineEvent,
        CreateGoogleEvent
    },
    
    data() {
        return {
            bookings : [],
            moment   : moment,
            pagination: {
                'current_page': 1
            },
            currency : null,
            booking_id : 0,
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

    methods:{
          // get all events
        getMyBookings() {
            
            axios.get(route('eventmie.mybookings')+'?page='+this.current_page)
            .then(res => {
                this.currency   = res.data.currency;
                this.bookings   = res.data.bookings.data;
                this.pagination = {
                    'total' : res.data.bookings.total,
                    'per_page' : res.data.bookings.per_page,
                    'current_page' : res.data.bookings.current_page,
                    'last_page' : res.data.bookings.last_page,
                    'from' : res.data.bookings.from,
                    'to' : res.data.bookings.to
                };
            })
            .catch(error => {
                
            });
        },

        // cancel my booking
        bookingCancel(booking_id, ticket_id, event_id) {
            this.showConfirm(trans('em.ask_cancel_booking')).then((res) => {
                if(res) {
                    axios.post(route('eventmie.mybookings_cancel'),{
                        booking_id : booking_id,
                        ticket_id  : ticket_id,
                        event_id   : event_id,
                    })
                    .then(res => {
                        if(res.data.status)
                        {
                            this.showNotification('success', trans('em.booking_cancel_success'));
                            this.getMyBookings();
                        }    
                    })
                    .catch(error => {});
                }
            })
        },

        // return route with event slug
        eventSlug(slug) {
            if(slug) {
                return route('eventmie.events_show',[slug]);
            }
        },

        // return route with download URL
        downloadURL(id, order_number) {
            if(id && order_number) {
                return route('eventmie.downloads_index',[id, order_number]);
            }
        },
    },
    mounted() {
        this.getMyBookings();
        
        // send email after successful bookings
        this.sendEmail();
    }
}
</script>



<template>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.events') }}</label>
                    <select class="form-control" name="state" v-model="event_id" >
                        <option  value=0>{{ trans('em.all_events') }} </option>
                        <option v-for="(event, index) in events" :key ="index" :value="event.id">{{event.title}} </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.booking_date') }} </label>
                    <date-picker  class="form-control" :shortcuts="shortcuts" v-model="date_range" range :lang="$vue2_datepicker_lang" :placeholder="trans('em.booking_date')" format="YYYY-MM-DD "></date-picker>
                </div>
            </div>    
        </div>
    </div>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12 table-responsive table-mobile">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('em.order_id') }}</th>
                            <th>{{ trans('em.event') }}</th>
                            <th>{{ trans('em.customer_email') }} </th>
                            <th>{{ trans('em.ticket') }}</th>
                            <th>{{ trans('em.order_total') }} </th>
                            <th>{{ trans('em.booked_on') }} </th>
                            <th>{{ trans('em.payment') }}</th>
                            <th>{{ trans('em.checked_in') }}</th>
                            <th>{{ trans('em.status') }}</th>
                            <th>{{ trans('em.cancellation') }}</th>
                            <th>{{ trans('em.expired') }}</th>
                            <th>{{ trans('em.download') }}</th>
                            <th>{{ trans('em.actions') }}</th>
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
                            <td :data-title="trans('em.customer_email')">{{ booking.customer_email}}</td>
                            <td :data-title="trans('em.ticket')">{{ booking.ticket_title }} <strong>{{ ' x '+booking.quantity }}</strong></td>
                            <td :data-title="trans('em.order_total')">{{ booking.net_price+' '+currency }}</td>
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
                            <td :data-title="trans('em.cancellation')">
                                <span class="lgx-badge lgx-badge-small lgx-badge-success" v-if="booking.booking_cancel == 0 && booking.status == 1">{{ trans('em.no') }}</span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-if="booking.booking_cancel == 0 && booking.status == 0">{{ trans('em.disabled') }}</span>
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
                                <div v-if="hide_ticket_download == null">
                                    <a v-if="booking.is_paid == 1 && booking.status == 1" class="lgx-btn lgx-btn-sm lgx-btn-success" :href="downloadURL(booking.id, booking.order_number)"><i class="fas fa-download"></i> {{trans('em.ticket')}}</a>
                                    <span class="lgx-badge lgx-badge-small lgx-badge-mute" v-else>
                                        <small v-if="booking.is_paid == 0 && booking.status == 1" class="text-small text-danger">{{ trans('em.unpaid') }}</small>
                                        <small v-else class="text-small">{{ trans('em.disabled') }}</small>
                                    </span>
                                </div><br>

                                <div v-if="booking.online_location != null && booking.is_paid == 1 && booking.status == 1"> 
                                    <button type="button" class="lgx-btn lgx-btn-sm" @click="booking_id = booking.id"><i class="fas fa-tv"></i> {{ trans('em.online_event')}}</button>
                                    <online-event  v-if="booking_id == booking.id" :online_location="booking.online_location" :booking_id="booking.id" ></online-event>
                                </div>
                            </td>
                            <td :data-title="trans('em.actions')">
                                <a class="lgx-btn lgx-btn-sm lgx-btn-black" :href="goto_route(booking.id)">
                                    <i class="fas fa-eye"></i> <span>{{ trans('em.view') }}</span>
                                </a><br>
                                <button type="button" class="lgx-btn lgx-btn-sm lgx-btn-info" @click="edit_index = index">
                                    <i class="fas fa-edit"> </i><span >{{ trans('em.edit') }}</span>
                                </button>
                                <edit-booking 
                                    :booking    = "booking"
                                    v-if="edit_index == index" 
                                    @changeItem = "getOrganiserBookings"

                                ></edit-booking>
                            </td>
                        </tr>
                
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center" v-if="bookings.length > 0">
                <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'/bookings'" @paginate="getOrganiserBookings()">
                </pagination-component>
            </div>       
        </div>
    </div>
</div>
</template>

<script>

import PaginationComponent from '../../common_components/Pagination'
import mixinsFilters from '../../mixins.js';
import EditBooking from './EditBooking.vue';
import DatePicker from 'vue2-datepicker';
import OnlineEvent from '../../bookings_customer/components/OnlineEvent';

export default {
    
    mixins:[
        mixinsFilters
    ],

    props: [
        // pagination query string
        'page',
        'is_success',
        'date_format',
        'hide_ticket_download'
        
    ],
    
    components: {
        PaginationComponent,
        EditBooking,
        DatePicker,
        OnlineEvent,
    },

    
    data() {
        return {
            bookings   : [],
            moment     : moment,
            edit_index : null,
             pagination: {
                'current_page': 1
            },
            currency   : null,

            date_range : [],
            start_date : '',
            end_date   : '',

            booking_id : 0,
            
            // date shortucts like today, tommorrow
            shortcuts: [
                {
                    text: trans('em.today'),
                    onClick: () => {
                        this.date_range = [moment().toDate(), moment().toDate() ]
                    }
                },
                {
                    text: trans('em.tomorrow'),
                    onClick: () => {
                        this.date_range = [moment().add(1,'day').toDate(), moment().add(1,'day').toDate()]
                    }
                },
                {
                    text: trans('em.this')+' '+trans('em.weekend'),
                    onClick: () => {
                        this.date_range = [moment().endOf("week").toDate(), moment().endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.this')+' '+trans('em.week'),
                    onClick: () => {
                        this.date_range = [moment().startOf("week").toDate(), moment().endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.next')+' '+trans('em.week'),
                    onClick: () => {
                        this.date_range = [moment().add(1, 'weeks').startOf("week").toDate(), moment().add(1, 'weeks').endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.this')+' '+trans('em.month'),
                    onClick: () => {
                        this.date_range = [moment().startOf("month").toDate(), moment().endOf("month").toDate()]
                    }
                },
                {
                    text: trans('em.next')+' '+trans('em.month'),
                    onClick: () => {
                        this.date_range = [moment().add(1, 'months').startOf("month").toDate(), moment().add(1, 'months').endOf("month").toDate()]
                    }
                },
            ],

            events    : [],
            event_id  : 0,
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
        getOrganiserBookings() {

            if(typeof this.start_date === "undefined") {
                this.start_date     = '';
            }
            if(typeof this.end_date === "undefined") {
                this.end_date     = '';
            }

            axios.get(route('eventmie.obookings_organiser_bookings')+'?page='+this.current_page+'&event_id='+this.event_id+'&start_date='
                         +this.start_date+'&end_date='+this.end_date)
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

        // view booking by organiser 
        organiserViewBooking(booking_id) {
            axios.get(route('eventmie.obookings_organiser_bookings_show',[booking_id]))
            .then(res => {
                if(res.data.status)
                {
                    this.getOrganiserBookings();
                }    
            })
            .catch(error => {
                
            });
        },

        // view booking
        goto_route(id) {
            return route('eventmie.obookings_organiser_bookings_show', {id:id});
        },

        // return route with event slug
        eventSlug(slug){
            
            if(slug)
            {
                return route('eventmie.events_show',[slug]);
            }    
        },

        // return route with download URL
        downloadURL(id, order_number) {
            if(id && order_number) {
                return route('eventmie.downloads_index',[id, order_number]);
            }
        },

        // searching by date 
        dateRange: function () {
            var is_date_null = 0;
            if(Object.keys(this.date_range).length > 0 )
            {
                // convert date range to server side date
                this.date_range.forEach(function(value, key) {
                    if(value != null) {
                        is_date_null = 1;

                        if(key == 0)
                            this.start_date   =  this.convert_date(value); // convert local start_date to server date then searching by date
                        
                        if(key == 1)
                            this.end_date     =  this.convert_date(value); // convert local end_date to server date then searching by date
                    }
                }.bind(this));
                
                // date reset
                if(is_date_null <= 0){
                    this.start_date  = '';
                    this.end_date    = '';
                }

                this.getOrganiserBookings()
            }
            
        },

        // get all events
        getMyEvents() {
            axios.get(route('eventmie.all_myevents'))
            .then(res => {
                this.events  = res.data.myevents;
            })
            .catch(error => {
                
            });
        },

        
    },
   
    mounted() {
        this.getOrganiserBookings();
        this.getMyEvents();
        
        // send email after successful bookings
        this.sendEmail();
    },

    watch : {
        date_range: function () {
            this.dateRange();
        },

        event_id: function () {
            this.getOrganiserBookings();
        },

    }
}
</script>



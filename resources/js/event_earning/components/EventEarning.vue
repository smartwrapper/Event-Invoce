<template>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="alert alert-info" role="alert">
                    <small>{{ trans('em.total_bookings') }}</small>
                    <small class="pull-right"><strong>{{ total_earning.customer_paid_total }} {{ currency }}</strong></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-danger" role="alert">
                    <small>{{ trans('em.total_admin_commission') }}</small>
                    <small class="pull-right"><strong>{{ total_earning.admin_commission_total }} {{ currency }}</strong></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="alert alert-success" role="alert">
                    <small>{{ trans('em.total_profit') }}</small>
                    <small class="pull-right"><strong>{{ total_earning.organiser_earning_total }} {{ currency }}</strong></small>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.events') }}</label>
                    <select class="form-control" name="state" v-model="event_id" >
                        <option  value=0>{{ trans('em.all_events') }}</option>
                        <option v-for="(event, index) in events" :key ="index" :value="event.id">{{event.title}} </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.booking_date') }}</label>
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
                            <th>{{ trans('em.event') }}</th>
                            <th>{{ trans('em.bookings') }}</th>
                            <th>{{ trans('em.commission') }}</th>
                            <th>{{ trans('em.profit') }}</th>
                            <th>{{ trans('em.month') }}</th>
                            <th>{{ trans('em.transferred') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(earning, index) in event_earning" :key="index" >
                            <td :data-title="trans('em.event')"><a :href="eventSlug(earning.event_slug)">{{ earning.event_name }}</a></td>
                            <td :data-title="trans('em.bookings')">{{ earning.customer_paid_total }} {{ currency }}</td>
                            <td :data-title="trans('em.commission')">{{ earning.admin_commission_total }} {{ currency }}</td>
                            <td :data-title="trans('em.profit')">{{ earning.organiser_earning_total }} {{ currency }}</td>
                            <td :data-title="trans('em.month')">{{ moment(earning.month_year, 'MM YYYY').format('MMM ,YYYY') }}</td>
                            <td :data-title="trans('em.transferred')">
                                <span class="lgx-badge lgx-badge-small lgx-badge-success" v-if="earning.transferred > 0">{{ trans('em.paid') }}</span>
                                <span class="lgx-badge lgx-badge-small lgx-badge-primary" v-else>{{ trans('em.pending') }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center" v-if="event_earning.length > 0">
                <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'/myearning'" @paginate="eventEarning()">
                </pagination-component>
            </div>       
        </div>
    </div>
</div>
</template>


<script>

import PaginationComponent from '../../common_components/Pagination'
import mixinsFilters from '../../mixins.js';



export default {
    
    mixins:[
        mixinsFilters
    ],

    props: [
        // pagination query string
        'page',
        
    ],
    
    components: {
        PaginationComponent,
    },

    
    data() {
        return {
            event_earning : [],
            total_earning : [],
            
            moment     : moment,
            edit_index : null,
             pagination: {
                'current_page': 1
            },
            currency   : null,

            date_range : [],
            start_date : '',
            end_date   : '',
            
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

        // return route with event slug
        eventSlug(slug){
            
            if(slug)
            {
                return route('eventmie.events_show',[slug]);
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

                this.eventEarning();
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

        // get event earning
        eventEarning() {
            
            if(typeof this.start_date === "undefined") {
                this.start_date     = '';
            }
            if(typeof this.end_date === "undefined") {
                this.end_date     = '';
            }

            axios.get(route('eventmie.organiser_event_earning')+'?page='+this.current_page+'&event_id='+this.event_id+'&start_date='
                         +this.start_date+'&end_date='+this.end_date)
            .then(res => {
                
                this.event_earning   = res.data.event_earning.data;
                this.pagination = {
                    'total' : res.data.event_earning.total,
                    'per_page' : res.data.event_earning.per_page,
                    'current_page' : res.data.event_earning.current_page,
                    'last_page' : res.data.event_earning.last_page,
                    'from' : res.data.event_earning.from,
                    'to' : res.data.event_earning.to
                };
            })
            .catch(error => {
                
            });
        },

        //net total
        totalEarning() {
             axios.get(route('eventmie.organiser_total_earning'))
            .then(res => {
                
                this.total_earning   = res.data.total_earning;
                this.currency   = res.data.currency;
                
            })
            .catch(error => {
                
            });
        }
    },
   
    mounted() {
        
        this.getMyEvents();
        this.eventEarning();
        this.totalEarning();
    },

    watch : {
        date_range: function () {
            this.dateRange();
        },

        event_id: function () {
            this.eventEarning();
        },

    }
}
</script>



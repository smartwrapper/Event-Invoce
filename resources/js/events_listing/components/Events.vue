<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-lg-3 mb-50 pl-30">
                
                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.search_event') }} </label>
                    <input type="text" class="form-control" v-model="f_search" :placeholder="trans('em.search_event_by')">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.category') }}</label>
                    <select class="form-control" name="category" v-model="f_category" >
                        <option  value="All">{{ trans('em.all') }}</option>
                        <option v-for="(category, index) in categories" :key ="index" :value="category.name">{{category.name}} </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.date') }}</label>
                    <date-picker  class="form-control" :shortcuts="shortcuts" v-model="date_range" range :lang="$vue2_datepicker_lang" :placeholder="trans('em.date_filter')" format="YYYY-MM-DD"></date-picker>
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.price') }}</label>
                    <select class="form-control" name="price" v-model="f_price">
                        <option value="">{{ trans('em.any_price') }}</option>
                        <option value="free">{{ trans('em.free') }}</option>
                        <option value="paid">{{ trans('em.paid') }}</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.country') }}</label>
                    <select class="form-control" name="country" v-model="f_country" >
                        <option  value="All">{{ trans('em.all') }}</option>
                        <option v-for="(country, index) in countries" :key ="index" :value="country.country_name">{{country.country_name}} </option>
                    </select>
                </div>

                <!-- <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.state') }}</label>
                    <select class="form-control" name="state" v-model="f_state" >
                        <option  value="All">{{ trans('em.all') }}</option>
                        <option v-for="(state, index) in states" :key ="index" :value="state.state">{{state.state}} </option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label for="exampleFormControlSelect1">{{ trans('em.city') }}</label>
                    <select class="form-control" name="city" v-model="f_city" :disabled="f_country == 'All'">
                        <option  value="All">{{ trans('em.all') }}</option>
                        <option v-for="(city, index) in cities" :key ="index" :value="city.city">{{city.city}} , {{city.state}} </option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="button" class="lgx-btn btn-block mt-2" @click="reset()"><i class="fas fa-redo"></i> {{ trans('em.reset_filters') }}</button>
                </div>
            </div>
        
            <div class="col-12 col-lg-9">
                <event-listing :events ="events" :currency="currency" :date_format="date_format"></event-listing>

                <hr>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <div class="column is-12" v-if="events.length > 0">
                            <pagination-component v-if="pagination.last_page > 1" :pagination="pagination" :offset="pagination.total" :path="'events'" @paginate="checkEvents()"></pagination-component>
                        </div>       
                    </div>
                </div>
            </div>
            
        </div>
    </div>
                            
</template>

<script>

import _ from 'lodash';

import PaginationComponent from '../../common_components/Pagination'
import EventListing from '../../common_components/EventListing';

import mixinsFilters from '../../mixins.js';
export default {
    props: [
        // pagination query string
        'page',
        'category',
        'search',
        'price',
        'city',
        'state',
        'country',
        'start_date',
        'end_date',
        'date_format'
    ],

    components: {
        PaginationComponent,
        EventListing, 
    },
    
    mixins:[
        mixinsFilters
    ],

    data() {
        return {
            events           : [],
            categories       : [],
            pagination: {
                'current_page': 1
            },
            moment           : moment,
            date_range       : [],
            f_price          : '',
            
            // filters
            f_category       : trans('em.all'),
            f_search         : '',

            // filter by location
            f_city           : trans('em.all'), 
            f_state          : trans('em.all'),
            f_country        : trans('em.all'),
            countries        :  [],
            states           :  [],
            cities           :  [],

            currency         : null,

            f_start_date     : '',
            f_end_date       : '',
            
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
                    text: trans('em.this_weekend'),
                    onClick: () => {
                        this.date_range = [moment().endOf("week").toDate(), moment().endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.this_week'),
                    onClick: () => {
                        this.date_range = [moment().startOf("week").toDate(), moment().endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.next_week'),
                    onClick: () => {
                        this.date_range = [moment().add(1, 'weeks').startOf("week").toDate(), moment().add(1, 'weeks').endOf("week").toDate()]
                    }
                },
                {
                    text: trans('em.this_month'),
                    onClick: () => {
                        this.date_range = [moment().startOf("month").toDate(), moment().endOf("month").toDate()]
                    }
                },
                {
                    text: trans('em.next_month'),
                    onClick: () => {
                        this.date_range = [moment().add(1, 'months').startOf("month").toDate(), moment().add(1, 'months').endOf("month").toDate()]
                    }
                },
            ],

        }
        
    },
    watch: {
        '$route' (to, from) {
            this.debouncedgGetEvents();    
        },
    
        // filters

        // searching f_category 
        f_category: function () {
            if(this.f_category)
            {
                this.$router.push({ query: Object.assign({}, this.$route.query, { category: this.f_category, page: 1  }) }).catch(()=>{});
            }
            else
            {
                let query = Object.assign({}, this.$route.query);
                delete query.category;
                this.$router.replace({ query });
            }
            
        },

        // seraching by f_search 
        f_search: function () {
            if(this.f_search)
            {
                this.$router.push({ query: Object.assign({}, this.$route.query, { search: this.f_search, page: 1  }) }).catch(()=>{});
            }
            else
            {
                let query = Object.assign({}, this.$route.query);
                delete query.search;
                this.$router.replace({ query });
            }    
        },
        // searching by date 
        date_range: function () {
            var is_date_null = true;
            if(this.date_range)
            {
                // convert date range to server side date
                this.date_range.forEach(function(value, key) {
                    if(value != null) {
                        is_date_null = false;

                        if(key == 0)
                            this.f_start_date   =  this.convert_date(value); // convert local start_date to server date then searching by date
                        
                        if(key == 1)
                            this.f_end_date     =  this.convert_date(value); // convert local end_date to server date then searching by date
                    }
                }.bind(this));
                
                if(is_date_null == false) {
                    this.$router.push({ query: Object.assign({}, this.$route.query, { start_date: this.f_start_date, page: 1  }) }).catch(()=>{});
                    this.$router.push({ query: Object.assign({}, this.$route.query, { end_date: this.f_end_date, page: 1  }) }).catch(()=>{});
                } else {
                    this.f_start_date  = '';
                    this.f_end_date    = '';
                    let query        = Object.assign({}, this.$route.query);
                    delete query.start_date;
                    delete query.end_date;
                    this.$router.replace({ query });
                }
            }
        },
        // searching by f_price 
        f_price: function() {
            if(this.f_price)
            {
                this.$router.push({ query: Object.assign({}, this.$route.query, { price: this.f_price, page: 1  }) }).catch(()=>{});
                
            }
            else
            {
                let query = Object.assign({}, this.$route.query);
                delete query.price;
                this.$router.replace({ query });
            }  
        },
        // seraching by f_city 
        f_city: function () {
            
            if(this.f_city)
            {
                this.$router.push({ query: Object.assign({}, this.$route.query, { city: this.f_city, page: 1  }) }).catch(()=>{});
            }
            else
            {
                let query = Object.assign({}, this.$route.query);
                delete query.city;
                this.$router.replace({ query });
            }    
        },

        // seraching by f_state 
        f_state: function () {
            if(this.f_state)
            {
                this.$router.push({ query: Object.assign({}, this.$route.query, { state: this.f_state, page: 1  }) }).catch(()=>{});
            }
            else
            {
                let query = Object.assign({}, this.$route.query);
                delete query.state;
                this.$router.replace({ query });
            }    
        },

        // searching f_country 
        f_country: function () {
        
            if(this.f_country)
            {
                let _this = this;

                if(_this.f_country == 'All')
                    _this.f_city = 'All';

                if(Object.entries(_this.countries).length > 0){
                    
                    let c     = Object.entries(_this.countries).find(obj => obj.city == _this.f_city); 
                    
                    if(c == undefined)
                        _this.f_city = trans('em.all');
                
                }   
                    
                this.$router.push({ query: Object.assign({}, this.$route.query, { country: this.f_country, page: 1  }) }).catch(()=>{});
            }
            else
            {
                let query = Object.assign({}, this.$route.query);
                delete query.country;
                this.$router.replace({ query });
            }


        },
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
        checkEvents() {
       
        },
        // get all events
        getEvents() {
            
            if(typeof this.f_start_date === "undefined") {
                this.f_start_date     = '';
            }
            if(typeof this.f_end_date === "undefined") {
                this.f_end_date     = '';
            }
            
            axios.get(route('eventmie.events')+'?page='+this.current_page+'&category='+encodeURIComponent(this.f_category)+'&search='+this.f_search+'&start_date='
                        +this.f_start_date+'&end_date='+this.f_end_date+'&price='+this.f_price+'&city='+this.f_city+'&state='+this.f_state+'&country='+encodeURIComponent(this.f_country))
            .then(res => {
                this.currency   = res.data.events.currency;
                this.events     = res.data.events.data;
                this.pagination = {
                    'total' : res.data.events.total,
                    'per_page' : res.data.events.per_page,
                    'current_page' : res.data.events.current_page,
                    'last_page' : res.data.events.last_page,
                    'from' : res.data.events.from,
                    'to' : res.data.events.to
                };
                this.countries = res.data.events.countries,
                this.states    = res.data.events.states,
                this.cities    = res.data.events.cities,
                // events sorting funtion
                this.eventSorting();
            })
            .catch(error => {
                
            });
        },

        // get categories
        getCategories(){
            axios.get(route('eventmie.myevents_categories'))
            .then(res => {
                if(res.status)
                   this.categories  = res.data.categories;
                
            })
            .catch(error => {
                
            });
        },

        // serch event with 5 delay
        debouncedgGetEvents: _.debounce(function() {
            this.getEvents()     
        }, 1000),

        // reset searching fields
        reset(){
            this.$router.replace({});
            this.f_search        = '';
            this.f_category      = trans('em.all');
            this.date_range      = '';
            this.f_start_date      = '';
            this.f_end_date        = '';
            this.f_price         = '';
            this.f_city          = trans('em.all');
            this.f_state         = trans('em.all');
            this.f_country       = trans('em.all');
        },

        // events sorting
        eventSorting(){
            if(this.events.length > 0)
            {
                let events_started = [];
                let events_ended   = [];
                let $this          = this;
                this.events.forEach(function(v,k) {
                    if(v.repetitive == 1) {
                        if(moment().format('YYYY-MM-DD') < $this.convert_date_to_local(v.end_date, 'YYYY-MM-DD')) {
                            events_started.push(v);
                        } else {
                            events_ended.push(v);
                        }
                    } else {
                        if(moment().format('YYYY-MM-DD') < $this.convert_date_to_local(v.start_date, 'YYYY-MM-DD')) {
                            events_started.push(v);
                        } else {
                            events_ended.push(v);
                        }
                    }
                })
                this.events = [];
                this.events.push(...events_started);
                this.events.push(...events_ended);

                return this.events;
            }
        },

        // set query string if have query string when page refresh
        setQueryString(){
            
            //set serarch
            this.f_search   = (typeof this.search !== 'undefined') ? decodeURIComponent(this.search) : '';

            // get category of title from welcome page's categories 
            this.f_category = this.category ? decodeURIComponent(this.category).replace(/\+/g, " ") : trans('em.all');

            // set price
            this.f_price     = (typeof this.price !== 'undefined') ? decodeURIComponent(this.price) : '';

             // set city
            this.f_city      = (typeof this.city !== 'undefined') ? decodeURIComponent(this.city) : trans('em.all');

             // set state
            this.f_state     = (typeof this.state !== 'undefined') ? decodeURIComponent(this.state) : trans('em.all');

            // set country 
            this.f_country   = this.country ? decodeURIComponent(this.country).replace(/\+/g, " ") : trans('em.all');

            // set date
            if((typeof this.start_date !== 'undefined') && (typeof this.end_date !== 'undefined' )){
                
                this.date_range   = [this.setDateTime(this.start_date), this.setDateTime(this.end_date) ];
            
                this.f_start_date = this.start_date;
                this.f_end_date   = this.end_date; 
            }     
            
            
        }   
        
    },
    mounted() {
        this.setQueryString();
        this.getEvents();
        this.getCategories();
        
    }
}
</script>
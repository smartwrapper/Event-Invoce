<template>
    <div class="tab-pane active">
        <div class="panel-group">
            <div class="panel panel-default lgx-panel">
                <div class="panel-heading">
                    
                    <form ref="form" @submit.prevent="validateForm" method="POST" enctype="multipart/form-data"  class="lgx-contactform">
                        <input type="hidden" name="event_id" v-model="event_id">
                        
                        <div class="row">

                            <div class="col-xs-12 col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ trans('em.start_date') }}</label>
                                    <date-picker 
                                        v-model="start_date" 
                                        type="date" 
                                        format="YYYY-MM-DD" 
                                        :placeholder="trans('em.start_date')"
                                        :class="'form-control'"
                                        :lang="$vue2_datepicker_lang"
                                    ></date-picker>
                                    <input type="hidden" class="form-control"  :value="convert_date(start_date)" name="start_date"  v-validate="'required'">
                                    <span v-show="errors.has('start_date')" class="help text-danger">{{ errors.first('start_date') }}</span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label for="start_time">{{ trans('em.start_time') }}</label>
                                    <date-picker 
                                        v-model="start_time" 
                                        type="time" 
                                        format="HH:mm" 
                                        :placeholder="trans('em.start_time')"
                                        :class="'form-control'"
                                        :lang="$vue2_datepicker_lang"
                                    ></date-picker>
                                    <input type="hidden" class="form-control"  :value="convert_time(start_time)" name="start_time"  v-validate="'required'">
                                    <span v-show="errors.has('start_time')" class="help text-danger">{{ errors.first('start_time') }}</span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ trans('em.end_date') }}</label>
                                    <date-picker 
                                        v-model="end_date" 
                                        type="date" 
                                        format="YYYY-MM-DD"
                                        :placeholder="trans('em.end_date')"
                                        :class="'form-control'"
                                        :lang="$vue2_datepicker_lang"
                                    ></date-picker>
                                    <input type="hidden" class="form-control"  :value="convert_date(end_date)" name="end_date"  v-validate="'required'">
                                    <span v-show="errors.has('end_date')" class="help text-danger">{{ errors.first('end_date') }}</span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-6">
                                <div class="form-group">
                                    <label for="end_time">{{ trans('em.end_time') }}</label>
                                    <date-picker 
                                        v-model="end_time" 
                                        type="time" 
                                        format="HH:mm" 
                                        :placeholder="trans('em.end_time')"
                                        :class="'form-control'"
                                        :lang="$vue2_datepicker_lang"
                                    ></date-picker>
                                    <input type="hidden" class="form-control"  :value="convert_time(end_time)" name="end_time" 	 v-validate="'required'">
                                    <span v-show="errors.has('end_time')" class="help text-danger">{{ errors.first('end_time') }}</span> 
                                </div>
                            </div>

                        </div>

                        <div v-if="editDateValidation()"
                         >
                            <hr><label class="text-info"> {{ trans('em.date_info') }}</label><hr>
                        </div>

                        <div class="row">
                            <div class="col-md-12" 
                                v-if="!repetitive && check_date(start_date) && check_date(end_date) && check_time(start_time) && check_time(end_time)"
                            >
                                <p class="text">{{ trans('em.start') }} {{ changeDateFormat(start_date, "YYYY-MM-DD") }} {{ trans('em.end') }} {{ changeDateFormat(end_date, "YYYY-MM-DD") }}</p>
                                
                                <!-- In case of simple : total hours (from start date to end date) -->
                                <h4 class="location">
                                    <strong>{{ trans('em.duration') }} </strong> 
                                    {{ countDays(start_date, end_date)+(countDays(start_date, end_date) > 1 ? ' days' : ' day')  }}
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                   
                                    {{ 
                                        counthours(moment(start_date).format('YYYY-MM-DD') +' '+ moment(start_time).format('HH:mm:ss '),
                                                moment(end_date).format('YYYY-MM-DD')+' '+ moment(end_time).format('HH:mm:ss ')) + 
                                        (counthours(moment(start_date).format('YYYY-MM-DD') +' '+ moment(start_time).format('HH:mm:ss '),
                                                moment(end_date).format('YYYY-MM-DD')+' '+ moment(end_time).format('HH:mm:ss ')) > 1 ? ' hours' : ' hour')
                                    }}
                                </h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="repetitive" value = "1"  name="repetitive" @change="isDirty()"> {{ trans('em.add_repetitive_schedules') }}
                                    </label>
                                </div>
                            </div>

                            
                            <div class="col-xs-12 col-sm-6 col-md-6" v-if="repetitive && repetitive_type.value != 1 && repetitive_type_temp != 1">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="merge_schedule" value = "1"  name="merge_schedule" @change="isDirty()"> 
                                        {{ trans('em.merge_schedule') }}
                                    </label>
                                </div>
                                <p v-if="merge_schedule <= 0">{{ trans('em.merge_false')}}</p>
                                <p v-if="merge_schedule > 0">{{ trans('em.merge_true')}}</p>
                            </div>
                           

                            <div class="col-xs-12 col-sm-8 col-md-8">
                                <div class="form-group" v-if="repetitive" >  
                                    <label>{{ trans('em.repetitive_type') }}</label>
                                    <multiselect 
                                        v-model="repetitive_type" 
                                        :options="repetitive_type_options"
                                        :placeholder="'-- '+trans('em.select_repetitive_type')" 
                                        label="text" 
                                        track-by="value" 
                                        :multiple="false"
                                        :close-on-select="true" 
                                        :clear-on-select="false" 
                                        :hide-selected="false" 
                                        :preserve-search="true" 
                                        :preselect-first="true"
                                        :allow-empty="false"
                                        @input="event_type"
                                        :class="'form-control'"
                                        @select="isDirty()"
                                    ></multiselect>
                                </div>
                            </div>
                        </div>

                        <div class="row"
                            v-if="validDate()" 
                        >
                            <div class="col-md-12">
                                <div 
                                    v-for="(item, index) in calculate_months" :key="index">
                                    <schedule-component
                                        :sch_index="index"
                                        :sch_r_type="r_type"
                                        :start_time_p="start_time"
                                        :end_time_p="end_time"
                                        :start_date_p="start_date"
                                        :end_date_p="end_date"
                                        :schedules_p="schedules.length ? schedules[index] : []"
                                        :month="calculate_months.length > 0 ? calculate_months[index] : 0"
                                        :months="calculate_months.length > 0 ? calculate_months : 0"
                                    >
                                    </schedule-component>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn lgx-btn btn-block"><i class="fas fa-sd-card"></i> {{ trans('em.save') }}</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations} from 'vuex';
import ScheduleComponent from './Schedule.vue';
import mixinsFilters from '../../mixins.js';

export default {

    props: [
        'server_timezone'
    ],

    mixins:[
        mixinsFilters
    ],

    components: {
        ScheduleComponent
    },

    data() {
        return {
            schedules           : [],
            moment              : moment,

            // local variable
            calculate_months    : [],
            r_type              : null,
                
            // important!!! declare all form fields
            start_time          : null,
            end_time            : null,
            start_date          : null,
            end_date            : null,
            repetitive          : 0, 
            merge_schedule      : 0,

            repetitive_type     : [],
            repetitive_type_options: [
                {value: 1, text: trans('em.daily')},
                {value: 2, text: trans('em.weekly')},
                {value: 3, text: trans('em.monthly')},
            ],

            //local timezone
            local_start_date   : null,
            local_end_date     : null,
            local_start_time   : null,
            local_end_time     : null,

            // for merge schedule
            repetitive_type_temp : 0,
        }
    },
    computed: {
        // get global variables
        ...mapState( ['event_id', 'v_repetitive', 'v_repetitive_days', 'v_repetitive_dates', 'v_from_time', 'v_to_time', 'organiser_id', 'event', 'is_dirty']),
    },

    methods: {
        // update global variables
        ...mapMutations(['add', 'update']),

        // reset form and close modal
        close: function () {
            this.$refs.form.reset();
        },

        // reset schedule data on check box and repetitive type
        reset_schedule(){
            this.add({ 
                v_repetitive         : this.repetitive,
            });

        },
       
        event_type(){
            // repetitive_type
            this.r_type         = this.repetitive_type ? this.repetitive_type.value : this.r_type;
        },

        // getSchedule
        getSchedule()
        {
            let post_url = route('eventmie.schedules');
            let post_data = {
                'event_id'         : this.event_id,
                'organiser_id'     : this.organiser_id
            };

            axios.post(post_url, post_data)
            .then(res => {
                this.schedules   = res.data.schedules;
                this.repetitive  = this.event.repetitive;
                this.merge_schedule = this.event.merge_schedule <= 0 ? false : true;
                // set selected repetitive type
               
                this.repetitive_type.push(this.repetitive_type_options[this.schedules[0].repetitive_type-1]);
                this.r_type      = this.repetitive_type[0].value; 
                
                var _this = this;
                if(this.schedules.length > 0){
                    this.calculate_months = [];
                    this.schedules.forEach(function(value, key) {
                        _this.calculate_months[key] = moment(value['from_date'], 'YYYY-MM-DD').format('YYYY-MM');
                        
                    });
                }
                

            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        },
        
        editEvent() {
            // server timezone change to local timezone
            this.convert_to_local_tz();

            this.start_date  = this.setDateTime(this.local_start_date);
            this.end_date    = this.setDateTime(this.local_end_date);
            this.start_time  = this.setDateTime(this.local_start_time);
            this.end_time    = this.setDateTime(this.local_end_time);
            
            // getSchedule if event is repetitive and in case of edit event
            if(this.event.repetitive == 1 && this.event_id)
            {
                this.getSchedule();

                // merge schedule
                this.repetitive_type_temp = this.event.repetitive_type;
                    
            }
            
        },

        // validate data on form submit
        validateForm(event) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.formSubmit(event);            
                }
            });
        },

        // show server validation errors
        serverValidate(serrors) {
            this.$validator.validateAll().then((result) => {
                this.$validator.errors.add(serrors);
            });
        },

        // make server side date with help of number then again convert server side date to number
        convertToServerDate(data) {
            
            
            
            var  data = data;
            var  v_repetitive_dates = [];
            var  date_number;
            var  count;
            
            data.forEach(function(value, key){
                
                if(value == null){
                    v_repetitive_dates[key] = null;
                    
                    return v_repetitive_dates;
                }

                v_repetitive_dates[key] = [];
                date_number             = value.split(',');
                count                   = date_number.length;
                
                if(Object.keys(date_number).length > 0){
                    date_number.forEach(function(date_number, key1){
                    
                        // 1.make date by number
                        // 2.convert local date to server side date
                        // 3.convert server side to number_date
                    

                        v_repetitive_dates[key] += moment(this.dateToFullDate(date_number, this.calculate_months[key]),"dddd LL").locale('en').format('DD');

                        // add comma except last key
                        if(key1 < (count-1) )
                            v_repetitive_dates[key] += ',';

                    }.bind(this));
                }
                
            }.bind(this));
            return  v_repetitive_dates;
        },

        // submit form
        formSubmit(event) {
            var  schedule_ids = []
       
            if(this.schedules.length > 0)
            {
                this.schedules.forEach(function (value, key) {
                    schedule_ids[key] = value.id;
                });
            }

            var v_repetitive_dates = [];
            var v_repetitive_days  = [];
            
            // v_repetitive_dates 
            if(this.v_repetitive_dates.length > 0)
            {   
                v_repetitive_dates  = this.convertToServerDate(this.v_repetitive_dates);
                
            }    
            // v_repetitive_days 
            if(this.v_repetitive_days.length > 0)    
                v_repetitive_days   = this.convertToServerDate(this.v_repetitive_days);
            
            // prepare form data for post request
            let post_url = route('eventmie.myevents_store_timing');
   
            let post_data = {
                // start_date
                'start_date'       : moment(this.start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD'),
                'end_date'         : moment(this.end_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD'),
                'start_time'       : moment(this.start_time).locale('en').format('HH:mm:ss'),
                'end_time'         : moment(this.end_time).locale('en').format('HH:mm:ss'),
                'repetitive'       : Number(this.repetitive),
                'merge_schedule'   : Number(this.merge_schedule),
                'repetitive_type'  : this.r_type,
                
                // schedule data (vuex data)
                'repetitive_days'  : v_repetitive_days,
                'repetitive_dates' : v_repetitive_dates,
                'from_time'        : this.v_from_time,
                'to_time'          : this.v_to_time,
                'event_id'         : this.event_id,
                'organiser_id'     : this.organiser_id,
                'schedule_ids'     : schedule_ids,
            };
            
            // axios post request
            axios.post(post_url, post_data)
            .then(res => {
                
                if(res.data.status)
                {
                    this.showNotification('success', trans('em.timings')+' '+trans('em.event_save_success'));
                }
                // reload page   
                setTimeout(function() {
                    location.reload(true);
                }, 1000);
                
            })
            .catch(error => {
                let serrors = Vue.helpers.axiosErrors(error);
                if (serrors.length) {
                    this.serverValidate(serrors);
                }
            });
        },

        //calculate months between two dates
        calculateMonth() {
            // reset/empty schedules vuex data
            
            this.add({ 
                v_repetitive_days   : [],
                v_repetitive_dates  : [],
                v_from_time         : [],
                v_to_time           : [],
            });
            // count months
            if(this.check_date(this.start_date) && this.check_date(this.end_date))
                this.calculate_months = this.countMonth(this.start_date, this.end_date);
        },

        // server time convert into local timezone
        convert_to_local_tz(){
            // this.local_start_date   = this.convert_date_to_local(this.event.start_date);
            // this.local_end_date     = this.convert_date_to_local(this.event.end_date);
            // this.local_start_time   = this.convert_time_to_local(this.event.start_date, this.event.start_time);
            // this.local_end_time     = this.convert_time_to_local(this.event.end_date, this.event.end_time);

            
            this.local_start_date   = this.userTimezone(this.event.start_date+' '+this.event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
            this.local_end_date     = this.userTimezone(this.event.end_date+' '+this.event.end_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
            this.local_start_time   = this.userTimezone(this.event.start_date+' '+this.event.start_time, 'YYYY-MM-DD HH:mm:ss');
            this.local_end_time     = this.userTimezone(this.event.end_date+' '+this.event.end_time, 'YYYY-MM-DD HH:mm:ss');
        },

        // check valid date 

        validDate(){
            var status = false;
           
           if(this.userTimezone(this.event.start_date+' '+this.event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD') != moment(this.start_date).format('YYYY-MM-DD')) {
                if(this.repetitive && 
                    this.check_date(this.start_date) && 
                    this.check_date(this.end_date) &&
                    this.check_time(this.start_time) && 
                    this.check_time(this.end_time) && 
                    !moment(this.start_date).isSame(this.moment(this.end_date), 'date') &&
                    moment(this.start_date).format('YYYY-MM-DD') >= moment().format('YYYY-MM-DD') &&
                    moment(this.start_date).format('YYYY-MM-DD') < moment(this.end_date).format('YYYY-MM-DD') 
                ) {
                
                    status = true
                    

                }
                else {
                
                    this.repetitive = 0;
                    this.merge_schedule = 0;
                    
                    status = false;
                }
            } else {

                status = true;
            }

            if(!this.repetitive || this.repetitive == 0){
                this.repetitive     = 0;
                this.merge_schedule = 0;
                
                status = false;
            }
            
            return status;
            
        },

        // edit date validation
        editDateValidation(){

            if(this.userTimezone(this.event.start_date+' '+this.event.start_time, 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD') != moment(this.start_date).format('YYYY-MM-DD')) {
                if(
                    moment(this.start_date).format('YYYY-MM-DD') <= moment().format('YYYY-MM-DD') ||
                    moment(this.start_date).format('YYYY-MM-DD') > moment(this.end_date).format('YYYY-MM-DD') 
                ) {
                    
                    return true;
                    

                }
                else {
                
                    return false;
                }
            } else {

                return false;
            }
        },

        isDirty() {
            this.add({is_dirty: true});
        },
        isDirtyReset() {
            this.add({is_dirty: false});
        },


    },
    
    watch: {
        start_date : function () {
            this.calculateMonth();
            this.schedules = [];

            if(this.local_start_date != this.convert_date(this.start_date)) {
                this.isDirty();
            }
        },
        end_date : function () {
            this.calculateMonth();     

            if(this.local_end_date != this.convert_date(this.end_date)) {
                this.isDirty();
            }

        },
        start_time : function () {
            if(this.event.start_time != this.convert_time(this.start_time)) {
                this.isDirty();
            }

        },
        end_time : function () {
            if(this.event.end_time != this.convert_time(this.end_time)) {
                this.isDirty();
            }
        },
        repetitive: function () {
            this.reset_schedule();
        },
        r_type : function() {   
            this.reset_schedule();
        },

        repetitive_type: function () {
            
            // merge schedule is not for daily event 
            if(this.repetitive_type)
            {
                if(this.repetitive_type.value == 1)
                    this.merge_schedule = 0;
                

                if(this.repetitive_type.value == 2 || this.repetitive_type.value == 3 )
                    this.repetitive_type_temp = 0;

            }
        },
    },

    mounted(){
        this.isDirtyReset();
        // if user have no event_id then redirect to details page
        let event_step  = this.eventStep();
        
        if(event_step)
        {
            var $this = this;
            this.getMyEvent().then(function (response){
                if(Object.keys($this.event).length)
                    $this.editEvent();
            });
        }

    }
    
}
</script>
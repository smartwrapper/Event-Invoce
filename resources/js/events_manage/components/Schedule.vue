<template>
    <div class="schedule-row">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-title">
                    <div class="schedule-number">
                        <div class="schedule-info">
                            <h4 class="time">#{{sch_index+1}} <span>{{ moment(month, 'YYYY-MM').format('MMMM') }} {{ trans('em.schedule') }}  </span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" v-if="sch_r_type == 2 ">
                <div class="form-group">
                    <label>{{ trans('em.repetitive_days') }}</label>
                    <multiselect
                        v-model="repetitive_days" 
                        :options="repetitive_days_options" 
                        :placeholder="trans('em.select_days')" 
                        label="text" 
                        track-by="value" 
                        :multiple="true"
                        :close-on-select="false" 
                        :clear-on-select="false" 
                        :hide-selected="false" 
                        :preserve-search="true" 
                        :preselect-first="schedules  ? false : true"
                        :allow-empty="true"
                        :disabled="sch_r_type == 3 || sch_r_type == 1 ? true : false "	
                        :class="'form-control'"
                        @input="schedules ? schedules.repetitive_type = null : ''"
                        @select="isDirty()"
                    >
                    </multiselect>
                </div>
            </div>
        
            <div class="col-md-12" v-if="sch_r_type == 3 || sch_r_type == 1">
                <div class="form-group">
                    <label v-if="sch_r_type == 3">{{ trans('em.repetitive_dates') }} ({{ trans('em.repeats_on') }})</label>
                    <label v-if="sch_r_type == 1">{{ trans('em.repetitive_dates') }} ({{ trans('em.repeats_except') }})</label>
                    <multiselect
                        v-model="repetitive_dates" 
                        :options="repetitive_dates_options" 
                        :placeholder="trans('em.select_dates')" 
                        label="text" 
                        track-by="value" 
                        :multiple="true"
                        :close-on-select="false" 
                        :clear-on-select="false" 
                        :hide-selected="false" 
                        :preserve-search="true" 
                        :preselect-first="schedules ? false : true"
                        :allow-empty="true"
                        :disabled="sch_r_type == 2  ? true : false "	
                        :class="'form-control'"
                        @input="schedules ? schedules.repetitive_type = null : ''"
                        @select="isDirty()"
                    >
                    </multiselect>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="from_time">{{ trans('em.start_time') }}</label><br>
                    <date-picker 
                        v-model="from_time" 
                        type="time" 
                        format="HH:mm" 
                        :placeholder="trans('em.select_start_time')" 
                        :class="'form-control'"
                        :lang="$vue2_datepicker_lang"
                        @change="isDirty()"
                    ></date-picker>
                    <span v-show="errors.has('end_time')" class="help text-danger">{{ errors.first('from_time') }}</span> 
                </div>
            </div>    

            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="to_time">{{ trans('em.end_time') }}</label><br>
                    <date-picker 
                        v-model="to_time" 
                        type="time" 
                        format="HH:mm" 
                        :placeholder="trans('em.select_end_time')" 
                        :class="'form-control'"
                        :lang="$vue2_datepicker_lang"
                        @change="isDirty()"
                    ></date-picker>
                    <span v-show="errors.has('to_time')" class="help text-danger">{{ errors.first('to_time') }}</span> 
                </div>
            </div>  
        </div>

        <div class="row" >

            <div class="col-md-12" v-if="sch_r_type && Object.keys(schedules).length > 0">
                <p class="text">  
                      
                    {{ trans('em.start') }}
                    {{ 

                        (moment(months[0], 'YYYY-MM')).isSame(moment(month, 'YYYY-MM')) ? 
                        changeDateFormat(convert_date_to_local(local_start_date), "YYYY-MM-DD") :
                        changeDateFormat(moment(month, 'YYYY-MM').startOf('month').format('YYYY-MM-DD hh:mm'), "YYYY-MM-DD") 
                    }} 
                    {{ trans('em.end') }} 
                    {{  
                        (moment(months[months.length -1 ], 'YYYY-MM')).isSame(moment(month, 'YYYY-MM')) ? 
                        changeDateFormat(convert_date_to_local(local_end_date), "YYYY-MM-DD") :
                        changeDateFormat(moment(month, 'YYYY-MM').endOf('month').format('YYYY-MM-DD hh:mm'), "YYYY-MM-DD") 
                    }} 
                </p>
                <!-- In case edit of repetitive : hours each day -->
                <h4 class="location" v-if="(
                    (schedules_p != undefined ) 
                    ? (
                    (schedules_p.from_time != null && schedules_p.to_time != null) || (from_time != 'Invalid Date' && to_time != 'Invalid Date' && 
                    from_time != null && to_time != null) 
                    ) 
                    : true
                    )   && (from_time != 'Invalid Date' && to_time != 'Invalid Date' && 
                    from_time != null && to_time != null) 
                ">
                    <strong>{{ trans('em.duration') }} </strong> 
                    {{ schedule_total_days()+(schedule_total_days() > 1 ? ' '+trans('em.days') : ' '+trans('em.day'))  }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    
                    {{ counthours(from_time, to_time, true)+(counthours(from_time, to_time, true) > 1 ? ' '+trans('em.hours') : ' '+trans('em.hour'))  }} {{ trans('em.each_day') }}

                </h4>
                <h4 class="location" v-else>
                    <strong>{{ trans('em.duration') }} </strong> 
                    {{  0   +' '+ trans('em.day')  }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    
                    {{ 0  +' '+ trans('em.hour')  }} {{ trans('em.each_day') }}

                </h4>
            </div>  
       
            <div class="col-md-12" v-if="Object.keys(schedules).length <= 0 && check_date(start_date) && check_date(end_date) && check_time(start_time) && check_time(end_time)">
                <p class="text">
                    {{ trans('em.start') }} 
                    {{
                        (moment(months[0], 'YYYY-MM')).isSame(moment(month, 'YYYY-MM')) ? 
                        changeDateFormat(convert_date_to_local(local_start_date), "YYYY-MM-DD") :
                        changeDateFormat(moment(month, 'YYYY-MM').startOf('month').format('YYYY-MM-DD hh:mm'), "YYYY-MM-DD") 
                    }} 
                    
                    {{ trans('em.end') }}                     {{ 
                        
                        (moment(months[months.length -1 ], 'YYYY-MM')).isSame(moment(month, 'YYYY-MM')) ? 
                        changeDateFormat(convert_date_to_local(local_end_date), "YYYY-MM-DD") :
                        changeDateFormat(moment(month, 'YYYY-MM').endOf('month').format('YYYY-MM-DD hh:mm'), "YYYY-MM-DD") 
                    }} 
                </p>
                <!-- In case create of repetitive : hours each day -->
                <h4 class="location" v-if="(
                    (schedules_p != undefined ) 
                    ? (
                    (schedules_p.from_time != null && schedules_p.to_time != null) || (from_time != 'Invalid Date' && to_time != 'Invalid Date' && 
                    from_time != null && to_time != null) 
                    ) 
                    : true
                    )   && (from_time != 'Invalid Date' && to_time != 'Invalid Date' && 
                    from_time != null && to_time != null) 
                ">
                    <strong>{{ trans('em.duration') }} </strong> 
                    {{ schedule_total_days()+(schedule_total_days() > 1 ? ' '+trans('em.days') : ' '+trans('em.day'))  }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    {{ counthours(from_time, to_time, true)+(counthours(from_time, to_time, true) > 1 ? ' '+trans('em.hours') : ' '+trans('em.hour')) }} {{ trans('em.each_day') }}
                </h4>

                 <h4 class="location" v-else>
                    <strong>{{ trans('em.duration') }} </strong> 
                    {{  0   +' '+ trans('em.day')  }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    
                    {{ 0  +' '+ trans('em.hour')  }} {{ trans('em.each_day') }}

                </h4>
            </div>

        </div>



    </div>
</template>

<script>

import { mapState, mapMutations} from 'vuex';
import mixinsFilters from '../../mixins.js';

export default {
    props: [
        'sch_index', 'sch_r_type', 'start_time_p', 'end_time_p', 'start_date_p', 'end_date_p', 'schedules_p', 'month', 'months'
    ],
    
    mixins:[
        mixinsFilters
    ],

    computed: {
        // get global variables
        ...mapState( ['v_repetitive', 'v_repetitive_days', 'v_repetitive_dates', 'v_from_time', 'v_to_time', 'is_dirty']),
    },

    data() {
        return {

            // parent date and time assign to child start and end date and start and time varible becaue you can't update prop value direct
            start_date     : this.start_date_p,
            end_date       : this.end_date_p,
            start_time     : this.start_time_p,
            end_time       : this.end_time_p,
            schedules      : this.schedules_p ? this.schedules_p : [],
            //end

            moment         : moment,
            repetitive_days: [{value : 1, text : trans('em.sunday') }],
            repetitive_days_options: [
                {value : 1, text : trans('em.sunday') },
                {value : 2, text : trans('em.monday') },
                {value : 3, text : trans('em.tuesday') },
                {value : 4, text : trans('em.wednesday') },
                {value : 5, text : trans('em.thursday') },
                {value : 6, text : trans('em.friday') },
                {value : 7, text : trans('em.saturday') },
                
            ],

            repetitive_dates : [{value : 1,   text : "1"}],
            repetitive_dates_options : [],
            
            
            from_time   : [],
            to_time     : [],

            //local timezone variable
            local_from_time     : null,
            local_to_time       : null,
            local_start_date    : null,
            local_end_date      : null,
            local_from_date     : null,
            local_to_date       : null,
         }
    },
    methods: {
          // update global variables
        ...mapMutations(['add', 'update']),
        
        // edit schedule
        editSchedule() {
            let $_this      = this;
            let schedules   = [0];
        
            //convert server timezone to local timezone
            this.convert_to_local_tz();

            this.start_date  = this.setDateTime(this.local_start_date);
            this.end_date    = this.setDateTime(this.local_end_date);
            this.from_time   = this.setDateTime(this.local_from_time); 
            this.to_time     = this.setDateTime(this.local_to_time);
            
            // it is already selected monthly dates
            if(Object.keys(this.schedules).length > 0 && this.schedules.repetitive_type == 3 )
            {
                //empty pre select
                this.repetitive_dates = [];

                if(this.schedules.repetitive_dates == null)
                {
                    return this.repetitive_dates;
                }

                schedules        = JSON.parse(this.schedules.repetitive_dates.split(','));   
            
                if(schedules.length > 0)
                {
                    schedules.forEach(function (value, key) {
                        $_this.repetitive_dates.push($_this.repetitive_dates_options[value-1]);
                    });
                }
            }

            // it is already selected daily dates
            if(Object.keys(this.schedules).length > 0 && this.schedules.repetitive_type == 1 )
            {
                //empty pre select
                this.repetitive_dates = [];

                if(this.schedules.repetitive_dates == null)
                {
                    return this.repetitive_dates;
                }

                schedules        = JSON.parse(this.schedules.repetitive_dates.split(','));   
                
                if(schedules.length > 0)
                {
                    schedules.forEach(function (value, key) {
                        $_this.repetitive_dates.push($_this.repetitive_dates_options[value-1]);
                    });
                }
            }

            // it is already selected weekly days
            if(Object.keys(this.schedules).length > 0 && this.schedules.repetitive_type == 2)
            {
                // empty pre select
                this.repetitive_days    = [];

                if(this.schedules.repetitive_days == null)
                {
                    return this.repetitive_days;
                }

                schedules               = this.schedules.repetitive_days.split(',');   
                
                if(schedules.length > 0)
                {
                    schedules.forEach(function (value, key) {
                        $_this.repetitive_days.push($_this.repetitive_days_options[value-1]);
                    });
                }
            }
        },
        
        // update schedule
        updateSchedule() {
            // Prepare post ready data
            
            // repetitive_days
            var tmp_repetitive_days = '';
            if(this.repetitive_days != null && Object.keys(this.repetitive_days).length > 0)
            {
                var count = this.repetitive_days.length;
                this.repetitive_days.forEach(function (value, key) {
                    tmp_repetitive_days += value.value;

                    // add comma except last key
                    if(key < (count-1) )
                        tmp_repetitive_days += ',';
                });
            }else{

                tmp_repetitive_days = null;
            }

            var tmp_repetitive_dates = '';
            
            
            if(this.repetitive_dates != null && Object.keys(this.repetitive_dates).length > 0)
            {
                var count = this.repetitive_dates.length;
                this.repetitive_dates.forEach(function (value, key) {
                    tmp_repetitive_dates += value.value;

                    // add comma except last key
                    if(key < (count-1) )
                        tmp_repetitive_dates += ',';
                });
            }else{

                tmp_repetitive_dates = null;
            }    


            this.update({ 
                v_sch_index         : this.sch_index, 
                v_repetitive_days   : tmp_repetitive_days,
                v_repetitive_dates  : tmp_repetitive_dates,
                v_from_time         : moment(this.from_time).locale('en').format('HH:mm:ss'),
                v_to_time           : moment(this.to_time).locale('en').format('HH:mm:ss'),
            });
        },

        // set selected start_time and end_time in from_time and to_time
        selectedTimes()
        {
            if(Object.keys(this.schedules).length <= 0)
            {
                this.from_time = this.start_time;
                this.to_time   = this.end_time;
            }
        },

        // server time convert into local timezone
        convert_to_local_tz(){
            this.local_start_date   = moment(this.start_date).format('YYYY-MM-DD');
            this.local_end_date     = moment(this.end_date).format('YYYY-MM-DD');


            this.local_from_time    = this.start_time;
            this.local_to_time      = this.end_time;
            this.local_from_date    = this.start_date;
            this.local_to_date      = this.end_date;


            if(Object.keys(this.schedules).length > 0 && this.schedules.repetitive_type != 2){
                this.local_from_time    = Object.keys(this.schedules).length > 0 ? moment(this.schedules.from_time, 'HH:mm:ss') : this.start_time;
                this.local_to_time      = Object.keys(this.schedules).length > 0 ? moment(this.schedules.to_time, 'HH:mm:ss')   : this.end_time;
                this.local_from_date    = Object.keys(this.schedules).length > 0 ? this.schedules.from_date : this.start_date;
                this.local_to_date      = Object.keys(this.schedules).length > 0 ? this.schedules.to_date   : this.end_date;
            }
            
            
            if(Object.keys(this.schedules).length > 0 && this.schedules.repetitive_type == 2){
                    
                this.local_from_time    = Object.keys(this.schedules).length > 0 ? this.userTimezone(this.schedules.from_date+' '+this.schedules.from_time, 'YYYY-MM-DD HH:mm:ss') : this.start_time;
                this.local_to_time      = Object.keys(this.schedules).length > 0 ? this.userTimezone(this.schedules.to_date+' '+this.schedules.to_time, 'YYYY-MM-DD HH:mm:ss')   : this.end_time;
            }
            

            
            
        },

        // totoal days
        schedule_total_days(){
            //count days in one month
            var  count_days       = moment(this.month, "YYYY-MM").locale('en').daysInMonth();
            var  total            = 0;

            
            //===========================EVENT CREATE CASE START ==========================================    
            // use this condition when user create event
            // total event's dates in daily event
            
            if(this.sch_r_type == 1 && this.repetitive_dates.length > 0 && ( Object.keys(this.schedules).length > 0 ? !this.schedules.repetitive_type : true
               && this.repetitive_dates != null
            ))
            {
                
                // first schedule total day because start date can start form between 
                if(this.months[0]== this.month && this.months.length != 1)
                {
                    
                    total        = count_days - moment(this.local_start_date, "YYYY-MM-DD").locale('en').format("DD")+1;
                    let count_d  = total;
                    
                    this.repetitive_dates.forEach(function(v,k){
                        // selected dates must be grather than start date
                        if(moment(this.local_start_date, "YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                        {
                            count_d     = count_d - 1;
                            total       = count_d; 
                        }    
                    }.bind(this))
                }

                // first schedule total day because end date can start form between
                else if(this.months[this.months.length-1] == this.month && this.months.length != 1)
                {
                    total        = moment(this.local_end_date, "YYYY-MM-DD").locale('en').format("DD"); 
                    let count_d  = total;  

                    this.repetitive_dates.forEach(function(v,k){
                        // selected dates must be less than end date
                        if(moment(this.local_end_date, "YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                        {
                            count_d     = count_d - 1;
                            total       = count_d; 
                            
                        }    
                    }.bind(this))                      
                }

                // total days for only one month
                else if(this.months.length == 1)
                {
                    var a = moment(this.local_start_date,"YYYY-MM-DD").locale('en');
                    var b = moment(this.local_end_date,"YYYY-MM-DD").locale('en');
                    
                    total        = b.diff(a, 'days')+1; 
                    let count_d  = total;  

                    this.repetitive_dates.forEach(function(v,k){

                        // selected date must be less than end date and grather than start date
                        if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD') && moment(this.local_end_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                        {
                            count_d     = count_d - 1;
                            total       = count_d; 
                            
                        }    
                        

                    }.bind(this))  
                }
                
                else
                {
                    total  = count_days - this.repetitive_dates.length; 
                }
                
                
            }
            // use this condition when user create event
            // total event's dates in monthly event
            if(this.sch_r_type == 3 && this.repetitive_dates.length > 0 && (Object.keys(this.schedules).length > 0 ? !this.schedules.repetitive_type : true))
            {
                total  = this.repetitive_dates.length; 
                
                // first schedule total day because start date can start form between
                if(this.months[0] == this.month && this.months.length != 1 )
                {
                    
                    let count_d  = 0 ;
                    this.repetitive_dates.forEach(function(v,k){
                        
                        // selected date must be grather than start date
                        if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format("YYYY-MM-DD") <= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                            count_d = count_d + 1;
                    }.bind(this));

                    total  = count_d;
                                             
                }

                // first schedule total day because end date can start form between
                if(this.months[this.months.length-1] == this.month && this.months.length != 1)
                {
                    let count_d  = 0 ;
                    this.repetitive_dates.forEach(function(v,k){
                        
                        // selected date must be less than end date
                        if(moment(this.local_end_date,"YYYY-MM-DD").locale('en').format("YYYY-MM-DD") >= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                            count_d = count_d + 1;
                    }.bind(this));

                    total    = count_d;
                       
                }

                // total days for only one month
                if(this.months.length == 1)
                {
                    var a = moment(this.local_start_date,"YYYY-MM-DD").locale('en');
                    var b = moment(this.local_end_date,"YYYY-MM-DD").locale('en');
                    
                    total        = b.diff(a, 'days')+1; 
                    
                    let count_d  = 0 ;
                    this.repetitive_dates.forEach(function(v,k){
                        
                        // selected date must be less than end date and grather than start date
                        if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD') && moment(this.local_end_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                            count_d = count_d + 1;
                    }.bind(this));

                    total    = count_d;
                    
                       
                }
            }
           
           // use this condition when user create event
            // total event's dates in weekly event
            if(this.sch_r_type == 2 && this.repetitive_days.length > 0 && (Object.keys(this.schedules).length > 0 ? !this.schedules.repetitive_type : true))
            {
                var all_dates = [];
                var i         = 1;
                var $_this    = this;
                var count     = 1;
                total         = 0;
                
                while( i <= count_days)
                {
                    // make dates object of moment according to months and year
                    
                    all_dates[i] = moment(this.month+'-'+i, "YYYY-MM-DD").format("YYYY-MM-DD");
                    i++;
                }
                
                all_dates.forEach(function(value, key){
                    $_this.repetitive_days.forEach(function(value1, key1){
                        
                        if(moment(value).format('dddd') == value1.text)
                        {
                            // first schedule total day because start date can start form between 
                            if(this.months[0] == this.month && this.months.length != 1)
                            {   
                                // selected date must be grather than start date
                                if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format("YYYY-MM-DD") <= moment(value).locale('en').format('YYYY-MM-DD'))  
                                {
                                    total = count++;
                                }                        
                            } 

                            // first schedule total day because end date can start form between
                            else if(this.months[this.months.length-1] == this.month && this.months.length != 1)
                            {
                                // selected date must be less than end date
                                if(moment(this.local_end_date,"YYYY-MM-DD").locale('en').format("YYYY-MM-DD") >= moment(value).locale('en').format('YYYY-MM-DD'))
                                    total = count++;                     
                                                        
                            }
                            else if(this.months.length == 1)
                            {   
                                // selected date must be less than end date and grather than start date
                                if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(value).locale('en').format('YYYY-MM-DD') && 
                                    moment(this.local_end_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(value).locale('en').format('YYYY-MM-DD')
                                   )
                                {   
                                    total = count++; 
                                }       
                            }
                            else
                            {
                                total = count++;
                            }
                        }
                    
                        
                    }.bind(this));
                }.bind(this));    
            }
            //===========================EVENT CREATE CASE END ==========================================    


            // ========================= EVENT EDIT CASE START =====================================

             // use this condition when user edit event
            // total event's dates in daily event
            if(Object.keys(this.schedules).length > 0)
            {   
                
                // total event's dates in daily event
                if(this.schedules.repetitive_type == 1  && this.schedules.repetitive_type && this.schedules.repetitive_dates != null )
                {
                    var repetitive_dates     = JSON.parse(this.schedules.repetitive_dates.split(','));  
                    

                    // first schedule total day because start date can start form between 
                    if(this.months[0] == this.month && this.months.length != 1)
                    {
                        total        = count_days - moment(this.local_start_date,"YYYY-MM-DD").locale('en').format("DD")+1;
                        let count_d  = total;
                        
                        repetitive_dates.forEach(function(v,k){

                            
                            // selected dates must be grather than start date
                            if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(this.month+'-'+v, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                            {
                                count_d     = count_d - 1;
                                total       = count_d; 
                            }    
                        }.bind(this))
                                                
                    }

                    // first schedule total day because end date can start form between
                    else if(this.months[this.months.length-1] == this.month && this.months.length != 1)
                    {
                        total        = moment(this.local_end_date,"YYYY-MM-DD").locale('en').format("DD"); 
                        let count_d  = total;  

                        repetitive_dates.forEach(function(v,k){
                           // selected dates must be less than end date
                            if(moment(this.local_end_date, "YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(this.month+'-'+v, 'YYYY-MM-DD').format('YYYY-MM-DD') )
                            {
                            
                                count_d     = count_d - 1;
                                total       = count_d; 
                                
                            }    
                        }.bind(this))                      
                    }
                    
                    // total days for only one month
                    else if(this.months.length == 1)
                    {
                        var a = moment(this.local_start_date,"YYYY-MM-DD").locale('en');
                        var b = moment(this.local_end_date,"YYYY-MM-DD").locale('en');
                        
                        total        = b.diff(a, 'days')+1; 
                        let count_d  = total;  

                        this.repetitive_dates.forEach(function(v,k){
                            // selected date must be less than end date and grather than start date
                            if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD') && moment(this.local_end_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                            {
                                count_d     = count_d - 1;
                                total       = count_d; 
                                
                            }    
                            

                        }.bind(this))  
                    }
                    
                    else
                    {
                        total  = count_days - repetitive_dates.length;
                        
                    }
                }

                // // use this condition when user edit event
                // // total event's dates in monthly event
                if(this.schedules.repetitive_type == 3  && this.schedules.repetitive_type && this.schedules.repetitive_dates != null)
                {
                    var repetitive_dates     = JSON.parse(this.schedules.repetitive_dates.split(','));  
                    total                    = repetitive_dates.length;

                    // first schedule total day because start date can start from between
                    if(this.months[0] == this.month && this.months.length != 1)
                    {
                        
                        let count_d  = 0 ;
                        repetitive_dates.forEach(function(v,k){

                            // selected date must be grather then start date
                            if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format("YYYY-MM-DD") <= moment(this.month+'-'+v, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                                count_d = count_d + 1;

                        }.bind(this));

                        total  = count_d;
                                                
                    }

                    // first schedule total day because end date can start form between
                    if(this.months[this.months.length-1] == this.month && this.months.length != 1)
                    {
                        
                        let count_d  = 0 ;
                        repetitive_dates.forEach(function(v,k){
                              
                            // selected date must be grather then start date
                            if(moment(this.local_end_date,"YYYY-MM-DD").locale('en').format("YYYY-MM-DD") >= moment(this.month+'-'+v, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                                count_d = count_d + 1;
                        }.bind(this));

                        total    = count_d;
                        
                    }

                    // total days for only one month
                    if(this.months.length == 1)
                    {
                        var a = moment(this.local_start_date,"YYYY-MM-DD").locale('en');
                        var b = moment(this.local_end_date,"YYYY-MM-DD").locale('en');
                        
                        total        = b.diff(a, 'days')+1; 

                        let count_d  = 0 ;
                        this.repetitive_dates.forEach(function(v,k){
                            
                            // selected date must be less than end date and grather than start date
                            if(moment(this.local_start_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') <= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD') && moment(this.local_end_date,"YYYY-MM-DD").locale('en').format('YYYY-MM-DD') >= moment(this.month+'-'+v.value, 'YYYY-MM-DD').format('YYYY-MM-DD'))
                                count_d = count_d + 1;
                        }.bind(this));

                        total    = count_d;
                        
                    }
                    
                }

                // // use this condition when user edit event
                // // total event's dates in weekly event
                if(this.schedules.repetitive_type == 2  && this.schedules.repetitive_type && this.schedules.repetitive_days != null)
                {
                    var all_dates = [];
                    var i         = 1;
                    var $_this    = this;
                    var count     = 1;
                    total         = 0;
                    
                    while( i <= count_days)
                    {
                        // make dates object of moment according to months and year
                        all_dates[i] = moment(this.month+'-'+i, "YYYY-MM-DD").format("YYYY-MM-DD");
                        i++;
                    }
                    
                    var repetitive_days_temp     = this.schedules.repetitive_days.split(','); 
                    
                    var repetitive_days          = [] 
                    
                    repetitive_days_temp.forEach(function(v, key){
                        
                        if(Number(v)==1)
                        repetitive_days[key]     = "Sunday";
                        if(Number(v)==2)
                            repetitive_days[key] = "Monday";
                        if(Number(v)==3)
                            repetitive_days[key] = "Tuesday";
                        if(Number(v)==4)
                            repetitive_days[key] = "Wednesday";
                        if(Number(v)==5)
                            repetitive_days[key] = "Thursday";
                        if(Number(v)==6)
                            repetitive_days[key] = "Friday";      
                        if(Number(v)==7)
                            repetitive_days[key] = "Saturday";        
                
                    }.bind(this));
                    
                    all_dates.forEach(function(value, key){
                        repetitive_days.forEach(function(value1, key1){
                         
                            if(moment(value).locale('en').format("dddd") == value1)
                            {
                                // first schedule total day because start date can start form between 
                                if(this.months[0] == this.month && this.months.length != 1)
                                {   
                                    // selected date must be grather than start date
                                    if(moment(this.local_start_date).format("YYYY-MM-DD") <= moment(value).format('YYYY-MM-DD'))  
                                    {
                                        total = count++;
                                    }                        
                                } 

                                // first schedule total day because end date can start form between
                                else if(this.months[this.months.length-1] == this.month && this.months.length != 1)
                                {
                                    // selected date must be less than end date
                                    if(moment(this.local_end_date).format("YYYY-MM-DD") >= moment(value).format('YYYY-MM-DD'))
                                        total = count++;                     
                                                            
                                }
                                // total days in one months
                                else if(this.months.length == 1)
                                {
                                    // selected date must be less than end date and grather than start date
                                    if(moment(this.local_start_date).format('YYYY-MM-DD') <= moment(value).format('YYYY-MM-DD') && 
                                        moment(this.local_end_date).format('YYYY-MM-DD') >= moment(value).format('YYYY-MM-DD')
                                    )
                                    {   
                                        total = count++; 
                                    }   
                                }
                                else
                                {
                                    total = count++;
                                }
                            }
                    
                        }.bind(this));
                    }.bind(this));    
                    
                }
            }    
            //===========================EVENT EDIT CASE END ==========================================    
            
            return total;
        },

        // make date option according to month
        make_date_options(){

            this.repetitive_dates_options = [];
            let month_end_date   = moment(this.month).daysInMonth();
            let i                = 1;
            
            for(i = 1; i <= month_end_date; i++)
            {
                this.repetitive_dates_options.push({value : i,text : i});

            }
        },

        isDirty() {
            this.add({is_dirty: true});
        }

    },

    watch: {
        v_repetitive : function() {
            this.updateSchedule();
            
        },
        sch_r_type : function () {
            this.updateSchedule();
            this.schedules = [];
        },
        repetitive_days : function () {
            this.schedule_total_days();
            this.updateSchedule();
        },
        repetitive_dates : function () {
            this.schedule_total_days();
            
            this.updateSchedule();
        },
        from_time : function () {
            
            this.updateSchedule();
        },
        to_time : function () {
            this.updateSchedule();
        },

        // parent component prop data
        start_date : function () {
            
            this.schedule_total_days();
            this.updateSchedule();
            this.convert_to_local_tz();
            
        },

        end_date : function () {
            
            this.schedule_total_days();
            this.updateSchedule();
        },

        // parent start date and it is already to local timezone
        start_date_p : function(){
            this.start_date = this.convert_date_to_local(this.start_date_p);
            this.schedules  = [];
            this.convert_to_local_tz();
            this.schedule_total_days();
            
        },

        // parent end date and it is already to local timezone
        end_date_p : function(){
            this.end_date = this.convert_date_to_local(this.end_date_p);
            this.schedules  = [];
            this.convert_to_local_tz();
            this.schedule_total_days();
        },

        // parent start time and it is already to local timezone
        start_time_p : function(){
            this.start_time = this.start_time_p;
            this.convert_to_local_tz();
        },

        // parent end time and it is already to local timezone
        end_time_p : function(){
            this.end_time = this.end_time_p;
            this.convert_to_local_tz();
        },

        // updates total days and schedule
        months : function(){
            this.convert_to_local_tz();
            this.schedule_total_days();
            this.updateSchedule();
            this.make_date_options();
            
        }

        
    },

    mounted(){
        // make date options dynamically
        this.make_date_options();
        this.selectedTimes();
        this.editSchedule();
        
        
    },
    
}
</script>
{{-- Organizer dashboard --}}
@extends(($isOrgDash ? 'eventmie::layouts.app' : 'voyager::master'))

@if($isOrgDash)
    @section('title')
        @lang('eventmie-pro::em.dashboard')
    @endsection
@else

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6 mb-0">
                    <h1 class="page-title">
                        <i class="voyager-boat"></i> {{ __('voyager::generic.Dashboard') }}
                    </h1>
                </div>
                <div class="col-xs-6 mb-0">
                    <div class="dropdown pull-right custom-dashboard-dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            {{ __('voyager::generic.Notifications') }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenu1">
                            @foreach ($total_notifications as $notification) 
                            <li>
                                <a href="{{ route('eventmie.notify_read', ['n_type' => $notification->n_type]) }}"> 
                                    {{ $notification->total }} {{ __('voyager::generic.new') }} 
                                    {{ $notification->n_type }}
                                </a>
                            </li>
                            @endforeach
                            <li role="separator" class="divider"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @stop
@endif

@section('content')
<div class="page-content browse container-fluid custom-dashboard">

    {{-- Admin only --}}
    @if(!$isOrgDash)    
        @include('voyager::alerts')
    @endif

    <div class="row">
        <div class="col-md-12">
            
            {{-- Admin Only --}}
            {{-- Stats --}}
            @if(!$isOrgDash)    
            <div class="row statistics">

                <div class="col-md-2">
                    <div class="box">
                        <i class="voyager-people text-center"></i>
                        <div class="info">
                            <h3>{{ $total_customers }}</h3> <p>{{ __('voyager::generic.Customers') }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box">
                        <i class="voyager-company text-center"></i>
                        <div class="info">
                            <h3>{{ $total_organizers }}</h3> <p>{{ __('voyager::generic.Organisers') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box">
                        <i class="voyager-calendar text-center"></i>
                        <div class="info">
                            <h3>{{ $total_events}}</h3> <p>{{ __('voyager::generic.Events') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="box">
                        <i class="voyager-ticket text-center"></i>
                        <div class="info">
                            <h3>{{ $total_bookings}}</h3> <p>{{ __('voyager::generic.Bookings') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <i class="voyager-dollar text-center"></i>
                        <div class="info">
                            <h3>{{ $total_revenue.' '.setting('regional.currency_default')}}</h3> <p>{{ __('voyager::generic.Revenue') }}</p>
                        </div>
                    </div>
                </div>

            </div>
            @endif

            {{-- Top selling events --}}
            <div class="panel panel-bordered">
                <div class="panel-body">
                        
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ __('voyager::generic.Top 10 Selling Events') }}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {!! $eventsChart->container() !!}
                        </div>
                    </div>
                    
                </div>
            </div>

            {{-- Admin Only --}}
            {{-- Sales report --}}
            @if(!$isOrgDash)    
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ __('voyager::generic.Event Sales Reports') }}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form>
                                <input type="hidden" name="_token" value="{{csrf_token() }}">
                                <div class="form-group">
                                    <label class="custom-control" id="event_id_label">{{ __('eventmie-pro::em.select').' '. __('eventmie-pro::em.event') }}</label>
                                    <select class="form-control" name="event_id" id="event_id">
                                        <option>{{ __('voyager::generic.all') }} {{ __('voyager::generic.Events') }}</option>
                                        @foreach ($events as $key => $value)
                                            <option value="{{ $value['id'] }}"> {{ $value['title'] }} </option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="custom-control" id="ticket_lable">{{ __('eventmie-pro::em.select').' '. __('eventmie-pro::em.ticket') }}</label>
                                    
                                    <select class="form-control" name="ticket" id="ticket"></select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <form  action="{{ $isOrgDash ? route('eventmie.export_sales_report') : route('voyager.export_sales_report') }}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token() }}">
                                <input type="hidden" name="export_event_id" id="export_event_id" > 
                                <input type="hidden" name="ticket_id" id="ticket_id"> 
                                <button type="submit"  id="export_button" class="btn lgx-btn btn-block" ><i class="fas fa-file-csv"></i> {{ __('eventmie-pro::em.export_sales_report') }} CSV </button>
                            </form>
                        </div>
                    </div>

                    <br>

                    <div class="row" >
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-hover table-condensed" id="sales_report">
                                <thead>
                                    <tr>
                                        <th>{{ __('voyager::generic.Order Number') }}</th>
                                        <th>{{ __('voyager::generic.Event') }}</th>
                                        <th>{{ __('voyager::generic.Timing') }}</th>
                                        <th>{{ __('voyager::generic.Customer') }}</th>
                                        <th>{{ __('voyager::generic.Booking') }} {{ __('voyager::generic.Date') }}</th>
                                        <th>{{ __('voyager::generic.Checked In') }}</th>
                                        <th>{{ __('voyager::generic.Ticket') }}</th>
                                        <th>{{ __('voyager::generic.Order') }} {{ __('voyager::generic.total') }}</th>
                                        <th>{{ __('voyager::generic.Organiser') }}</th>
                                        <th>{{ __('voyager::generic.Organiser Earning') }}</th>
                                        <th>{{ __('voyager::generic.Admin Commission') }}</th>
                                        <th>{{ __('voyager::generic.Admin Tax') }}</th>
                                        <th>{{ __('voyager::generic.Payout') }}</th>
                                    </tr>      
                                </thead>		
                                
                                <tfoot class="custom-table-foot" id="tfoot">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            {{-- Tickets Stats --}}
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ __('eventmie-pro::em.event_tickets_statistics') }}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <form>
                                <input type="hidden" name="_token" value="{{csrf_token() }}">
                                <div class="form-group">
                                    <select class="form-control" name="event_id_ticket_stats" id="event_id_ticket_stats">
                                        <option>{{ __('voyager::generic.all') }} {{ __('voyager::generic.Events') }}</option>
                                        @foreach ($events as $key => $value)
                                            <option value="{{ $value['id'] }}"> {{ $value['title'] }} </option>
                                        @endforeach 
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-hover table-condensed" id="event_total">
                                <thead>
                                    <tr>
                                        <th>{{ __('voyager::generic.Event') }}</th>
                                        <th>{{ __('voyager::generic.Ticket') }}</th>
                                        <th>{{ __('eventmie-pro::em.quantity') }}</th>
                                        <th>{{ __('voyager::generic.Order') }} {{ __('voyager::generic.total') }}</th>
                                      
                                    </tr>      
                                </thead>		
                                <tfoot class="custom-table-foot" id="tfoot">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                       
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('javascript')

@if($isOrgDash) 
<script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
@endif


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
{!! $eventsChart->script() !!}

<script>
var url                  = {!! json_encode(($isOrgDash ? route('eventmie.sales_report') : route('voyager.sales_report') )) !!}
var token                = {!! json_encode(csrf_token()) !!}
var isOrgDash            = {!! json_encode($isOrgDash, JSON_HEX_APOS) !!}

function sales_report(ticket_id = null) {
    
    export_sales_report();

    $('#sales_report').DataTable({
        processing : true,
        serverSide : true,
        searching: false,
        lengthMenu: [ 
            [10, 25, 50, -1], 
            [10, 25, 50, "All"] 
        ],                
        
        ajax: {
            url  : {!! json_encode(($isOrgDash ? route('eventmie.sales_report') : route('voyager.sales_report'))) !!},
            type :'POST',
            data : { 
                _token   : {!! json_encode(csrf_token()) !!},
                event_id : document.getElementById("event_id").value,
                ticket   : ticket_id,
        
            },
        },

        columns: [
            
            { data: 'order_number',   name: 'order_number' ,render:function(data, type, row){
                return '<small class="text-bold">'+row.order_number+'</small>';
            }},
            { data: 'event_title',   name: 'event_title' ,render:function(data, type, row){
                return '<small class="text-bold">'+row.event_title+'</small>';
            }},
            { data: 'event_start_date',   name: 'event_start_date' ,render:function(data, type, row){
                return row.event_start_date +' - <p>'+ row.event_end_date+'</p>';
            }},
            { data: 'customer_name',   name: 'customer_name' ,render:function(data, type, row){
                return row.customer_name +' <p>('+ row.customer_email+')</p>';
            }},
            { data: 'created_at',         name: 'created_at',render:function(data, type, row){
                
                return moment(row.created_at,'YYYY-MM-DD').format('{!! format_js_date() !!}');
            }},
            { data: 'checked_in',         name: 'checked_in' , render:function(data, type, row){
                return row.checked_in > 0 ? "@lang('eventmie-pro::em.yes')"+" ("+row.checked_in+"/"+row.quantity+")" : "@lang('eventmie-pro::em.no')"
            } },
            { data: 'ticket_price',       name: 'ticket_price', render:function(data, type, row){
                return row.ticket_price +' '+ row.currency+'<p>('+row.ticket_title+'<strong class="text-bold"> X '+row.quantity+'</strong>'+')</p>';
            }},
            { data: 'net_price',              name: 'net_price', render:function(data, type, row){
                return row.net_price +' '+ row.currency 
            }},
            { data: 'name',         name: 'name' , render:function(data, type, row){
                return row.name +' <p>('+ row.email+')</p>';
            } },
            { data: 'organiser_earning',         name: 'organiser_earning' , render:function(data, type, row){
                return row.organiser_earning ? row.organiser_earning : 0+' '+row.currency;
            } },
            { data: 'admin_commission',         name: 'admin_commission' , render:function(data, type, row){
                return row.admin_commission ? row.admin_commission : 0+' '+row.currency;
            } },
            { data: 'admin_tax',         name: 'admin_tax' , render:function(data, type, row){
                return row.admin_tax ? row.admin_tax : 0+' '+row.currency;
            } },
            { data: 'transferred',        name: 'transferred' , render:function(data, type, row){
                return (row.transferred <= 0 && row.organiser_earning > 0) ? "@lang('eventmie-pro::em.pending')" : "@lang('eventmie-pro::em.transferred')"; 
            }},
        ],

        footerCallback : function ( row, data, start, end, display ) {
            var currency = data.length > 0 ? data[0].currency : '' ;
            var api = this.api(), data;
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // computing column Total of the complete result 
            var total_ticket_price = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Update footer by showing the total with the reference of the column index 
            var msg = "@lang('eventmie-pro::em.total')";
            $( api.column( 0 ).footer() ).html(msg);
            $( api.column( 6 ).footer() ).html(total_ticket_price.toFixed(2)+' '+ currency);

            
            // computing column Total of the complete result 
            var customer_paid = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 7 ).footer() ).html(customer_paid.toFixed(2)+' '+ currency);    
                
                // computing column Total of the complete result 
            var organiser_earning = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 9 ).footer() ).html(organiser_earning.toFixed(2)+' '+ currency);    


            // computing column Total of the complete result 
            var admin_commission = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 10 ).footer() ).html(admin_commission.toFixed(2)+' '+ currency);    
                
            // computing column Total of the complete result 
            var admin_tax = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
                
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 11 ).footer() ).html(admin_tax.toFixed(2)+' '+ currency);    
                
        },

        drawCallback: function( settings ) {
            window.sales_report_data =  settings.json.data;

        }
    });

}  

function export_sales_report(ticket_id = null){
    
    // set event_id for export event sale report           
    document.getElementById("export_event_id").value = document.getElementById("event_id").value;

    // disable button if not selected event
    document.getElementById("export_button").disabled = parseInt(document.getElementById("event_id").value) > 0 ? false : true;
    if(parseInt(document.getElementById("event_id").value) > 0) {
        document.getElementById("export_button").classList.add("btn-primary");
    } else {
        document.getElementById("export_button").classList.remove("btn-primary");
    }

    // hide seach by ticket if not selected event
    document.getElementById("ticket").style.display = parseInt(document.getElementById("event_id").value) > 0 ? 'block' : 'none';

    document.getElementById("ticket_id").value = ticket_id;

    document.getElementById("ticket_lable").style.display   = parseInt(document.getElementById("event_id").value) > 0 ? 'block' : 'none';

        
}

function events_total_by_sales_price() {
    
    $('#event_total').DataTable({
        processing : true,
        serverSide : true,
        searching: false,
        lengthMenu: [ 
            [10, 25, 50, 'all'], 
            [10, 25, 50, "All"] 
        ],                
        
        ajax: {
            url  : {!! json_encode(($isOrgDash ? route('eventmie.event_total_by_sales_price') : route('voyager.event_total_by_sales_price'))) !!},
            type :'POST',
            data : { 
                _token   : {!! json_encode(csrf_token()) !!},
                event_id : document.getElementById("event_id_ticket_stats").value
        
            },
        },

        columns: [
            
            { data: 'title',   name: 'title' ,render:function(data, type, row){
                return '<small class="text-bold">'+row.title+'</small>';
            }},
            
            { data: 'tickets.',          name: 'tickets', orderable: false},
            { data: 'tickets_quantity',  name: 'tickets_quantity', orderable: false},
            { data: 'total_price',       name: 'total_price', orderable: false}
            
        ],

        footerCallback : function ( row, data, start, end, display ) {
            var currency = data.length > 0 ? data[0].currency : '' ;
            var api = this.api(), data;
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
        },

        drawCallback: function( settings ) {
            var all =  settings.json.recordsTotal;
        
            $('select[name^="event_total_length"] option[value="all"]').attr("value", all)
        }
    });

} 

function getEvent(){
    var url                  = {!! json_encode(($isOrgDash ? route('eventmie.get_event') : route('voyager.get_event'))) !!}

    $.ajax(url,{
        
            type: 'POST',  // http method
            data: { 
                _token   : {!! json_encode(csrf_token()) !!},
                event_id: document.getElementById("event_id").value,
            
            },  // data to submit
            success: function (res, status, xhr) {// success callback function
                
                if(res.status){
                    
                    $('#ticket').empty();

                    var mySelect = $('#ticket');
                    
                    mySelect.append(
                            $('<option></option>').val({!! json_encode( __('eventmie-pro::em.all')) !!}).html({!! json_encode(__('eventmie-pro::em.all')) !!}  )
                        );
                                
                    $.each(res.event.tickets, function(key, val) {
                        
                        mySelect.append(
                            $('<option></option>').val(val.id).html(val.title)
                        );
                    });
                }
        }
    });
}


// event sales reports
$('#event_id').change(function(){
    $('#sales_report').DataTable().destroy();
    $('#event_total').DataTable().destroy();
    
    sales_report();  
    events_total_by_sales_price(); 
    getEvent();
});

// filter by ticket
$('#ticket').change(function(){
    let ticket    = document.getElementById("ticket");
    let ticket_id = ticket.options[ticket.selectedIndex].value;
    
    $('#sales_report').DataTable().destroy();
    $('#event_total').DataTable().destroy();
    
    sales_report(ticket_id);  
    events_total_by_sales_price(); 
    export_sales_report(ticket_id);
});

// event tickets stats
$('#event_id_ticket_stats').change(function(){
    $('#event_total').DataTable().destroy();
    
    events_total_by_sales_price(); 
});

// call fucntion

/* Only Admin */
if(!isOrgDash) {
    sales_report(); 
}

events_total_by_sales_price();  

</script>
@stop
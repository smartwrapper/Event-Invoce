@extends('eventmie::layouts.app')

{{-- Page title --}}
@section('title')
    @if(empty($event)) 
        @lang('eventmie-pro::em.create_event')
    @else
        @lang('eventmie-pro::em.update_event')
    @endif
@endsection

    
@section('content')

<main>
    <!--SCHEDULE-->
    <div class="lgx-post-wrapper">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-tab">
                            <tabs-component :is_publishable="{{ !empty($event->is_publishable) ? $event->is_publishable : "{}" }}" :event_id="{{ !empty($event) ? $event->id : 0 }}"  :organiser_id="{{$organiser_id}}" :event_ck="{{ json_encode($event, JSON_HEX_APOS) }}"></tabs-component>
                            
                            <div class="tab-content lgx-tab-content lgx-tab-content-event">
                                <router-view 
                                    :is_admin="{{ json_encode(Auth::user()->hasRole('admin'))}}"
                                    :organisers="{{ json_encode($organisers, JSON_HEX_APOS) }}" 
                                    :organiser_id="{{$organiser_id}}"
                                    :event_ck="{{ json_encode($event, JSON_HEX_APOS) }}"
                                    :selected_organiser="{{ json_encode($selected_organiser, JSON_HEX_APOS) }}" 
                                    :server_timezone="{{ json_encode(setting('regional.timezone_default'), JSON_HEX_APOS)}}"
                                >
                                    
                                </router-view>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--SCHEDULE END-->
</main>

@endsection

@section('javascript')
<script>    
    var is_event_id    = {!! (!empty($event) ? $event->id : 0) !!};
</script>
<script type="text/javascript" src="{{ eventmie_asset('js/events_manage_v1.7.js') }}"></script>

@stop

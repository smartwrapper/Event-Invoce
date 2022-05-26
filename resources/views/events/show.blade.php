@extends('eventmie::layouts.app')

@section('title', $event->title)
@section('meta_title', $event->meta_title)
@section('meta_keywords', $event->meta_keywords)
@section('meta_description', $event->meta_description)
@section('meta_image', '/storage/'.$event['thumbnail'])
@section('meta_url', url()->current())

    
@section('content')

<!--BANNER-->
<section>
    <div class="lgx-banner event-poster background-tint" style="background-image: url({{ '/storage/'.$event['poster'] }});">
        <div class="lgx-banner-style">
            <div class="lgx-inner lgx-inner-fixed">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="lgx-banner-info-area">
                                <div class="lgx-banner-info text-center">
                                    <h2 class="title">&nbsp;</h2>
                                    <h3 >&nbsp;</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--//.ROW-->
                </div>
                <!-- //.CONTAINER -->
            </div>
            <!-- //.INNER -->
        </div>
    </div>
</section>
<!--BANNER END-->

<!--ABOUT-->
<section>
    <div id="lgx-about" class="lgx-about">
        <div class="mt-30 mb-50 mt-mobile-0">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-4 offset-md-1 visible-lg visible-md">
                        <div class="lgx-banner-info-area">
                            <div class="lgx-banner-info-circle lgx-info-circle">
                                <div class="info-circle-inner" style="background-image: url({{ eventmie_asset('img/bg-wave-circle.png') }});">
                                    <h3 class="date">
                                        
                                        {{ userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'd') }} 

                                        <span>
                                            {{ userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'M-Y') .'('. showTimezone() .')'}}
                                            
                                        </span>
                                    </h3>
                                    <div class="lgx-countdown-area">
                                        <!-- Date Format :"Y/m/d" || For Example: 1017/10/5  -->
                                        <div id="lgx-countdown" 
                                            data-date="{{userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'Y/m/d')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="lgx-about-content-area">
                            <div class="lgx-heading">
                                <h2 class="heading">{{ $event['title'] }}</h2>
                                <h3 class="subheading">
                                    @if(!empty($event['online_location']))
                                        <span class="lgx-badge lgx-badge-online"><i class="fas fa-signal"></i>&nbsp; @lang('eventmie-pro::em.online_event')</span>
                                    @endif
                                    
                                    <span class="lgx-badge lgx-badge-primary">{{ $category['name'] }}</span>

                                    @if(!empty($free_tickets))
                                        <span class="lgx-badge lgx-badge-primary">@lang('eventmie-pro::em.free_tickets')</span>
                                    @endif

                                    @if($event->repetitive)
                                        @if($event->repetitive_type == 1)
                                            <span class="lgx-badge lgx-badge-primary">
                                                @lang('eventmie-pro::em.repetitive_daily_event')
                                            </span>
                                        @elseif($event->repetitive_type == 2)    
                                            <span class="lgx-badge lgx-badge-primary">
                                                @lang('eventmie-pro::em.repetitive_weekly_event')
                                            </span>
                                        @elseif($event->repetitive_type == 3)    
                                            <span class="lgx-badge lgx-badge-primary">
                                                @lang('eventmie-pro::em.repetitive_monthly_event')
                                            </span>
                                        @endif    
                                        
                                    @endif
                                    
                                    @if($ended)   
                                        <span class="lgx-badge lgx-badge-danger">@lang('eventmie-pro::em.event_ended')</span>
                                    @endif
                                </h3>

                                <h3 class="subheading share-btns">
                                    <span><strong>@lang('eventmie-pro::em.share_event') &nbsp;</strong></span>

                                    <span><a class="btn btn-sm" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"><i class="fab fa-facebook-square"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ url()->current() }}"><i class="fab fa-twitter"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}&title={{ urlencode($event->title) }}"><i class="fab fa-linkedin"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="https://wa.me/?text={{ url()->current() }}"><i class="fab fa-whatsapp"></i></a></span>
                                    <span><a class="btn btn-sm" target="_blank" href="https://www.reddit.com/submit?title={{ urlencode($event->title) }}&url={{ url()->current() }}"><i class="fab fa-reddit"></i></a></span>
                                    <span><a class="btn btn-sm" href="javascript:void(0)" onclick="copyToClipboard()"><i class="fas fa-link"></i></a></span>
                                </h3>

                                <a class="lgx-btn lgx-btn-red mt-2" href="#buy-tickets"><i class="fas fa-ticket-alt"></i> @lang('eventmie-pro::em.get_tickets')</a>
                                
                            </div>
                            <div class="lgx-about-content">{!! $event['description'] !!}</div>
                        </div>
                    </div>

                </div>
                <br><br>
                <div class="row">
                    <div class="col-12 col-sm-5 col-md-5 offset-md-1">
                        <div class="lgx-about-service">
                            <div class="lgx-single-service lgx-single-service-color">
                                <div class="text-area">
                                    <span class="icon col-white"><i class="fas fa-map-marked-alt" aria-hidden="true"></i></span>
                                    <h2 class="title col-white">@lang('eventmie-pro::em.where')</h2>
                                    <p>
                                        @if(!empty($event['online_location']))
                                            <strong>@lang('eventmie-pro::em.online_event')</strong> <br>
                                        @endif

                                        <strong>{{$event->venue}}</strong> <br>
                                        
                                        @if($event->address)
                                        {{$event->address}} {{ $event->zipcode }} <br>
                                        @endif
                                        
                                        @if($event->city)
                                        {{ $event->city }}, 
                                        @endif
                                        
                                        @if($event->state)
                                        {{ $event->state }}, 
                                        @endif
                                        
                                        @if($country)
                                            {{ $country->get('country_name') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5 col-md-5">
                        <div class="lgx-about-service">
                             <div class="lgx-single-service lgx-single-service-color">
                                 <div class="text-area">
                                    <span class="icon col-white"><i class="fas fa-stopwatch" aria-hidden="true"></i></span>
                                    <h2 class="title col-white">@lang('eventmie-pro::em.when')</h2>
                                    
                                    @if(!$event->repetitive)
                                    <p>
                                        {{ userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', format_carbon_date(false)) }}

                                        {{ '('. showTimezone() .')' }}

                                        <br>@lang('eventmie-pro::em.till')<br>

                                        {{ userTimezone($event->end_date.' '.$event->end_time, 'Y-m-d H:i:s', format_carbon_date(false)) }}

                                        {{ '('. showTimezone() .')' }}
                                    </p>
                                    @else
                                    <p>
                                        {{ userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', format_carbon_date(true)) }}
                                        {{ '('. showTimezone() .')' }}
                                        <br>@lang('eventmie-pro::em.till')<br>
                                        
                                        {{ userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', 'Y-m-d') <= userTimezone($event->end_date.' '.$event->end_time, 'Y-m-d H:i:s', 'Y-m-d') ? userTimezone($event->end_date.' '.$event->end_time, 'Y-m-d H:i:s', format_carbon_date(true)) : userTimezone($event->start_date.' '.$event->start_time, 'Y-m-d H:i:s', format_carbon_date(true))}}
                                        {{ '('. showTimezone() .')' }}
                                    </p>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </div><!-- //.INNER -->
    </div>
</section>
<!--ABOUT END-->

<!--SCHEDULE-->
<section>
    <div id="buy-tickets" class="lgx-schedule">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-registration-area-simple">
                            <div class="lgx-heading lgx-heading-white">
                                <h2 class="heading">@lang('eventmie-pro::em.get_tickets')</h2>
                                <h3 class="subheading">@lang('eventmie-pro::em.select_schedule')</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <select-dates 
                        :event="{{ json_encode($event, JSON_HEX_APOS) }}" 
                        :max_ticket_qty="{{ json_encode($max_ticket_qty, JSON_HEX_APOS) }}"
                        :login_user_id="{{ json_encode(\Auth::id(), JSON_HEX_APOS) }}"
                        :is_customer="{{ Auth::id() ? (Auth::user()->hasRole('customer') ? 1 : 0) : 1 }}"
                        :is_organiser="{{ Auth::id() ? (Auth::user()->hasRole('organiser') ? 1 : 0) : 0 }}"
                        :is_admin="{{ Auth::id() ? (Auth::user()->hasRole('admin') ? 1 : 0) : 0 }}"
                        :is_paypal="{{ $is_paypal }}"
                        :is_offline_payment_organizer="{{ setting('booking.offline_payment_organizer') ? 1 : 0 }}"
                        :is_offline_payment_customer="{{ setting('booking.offline_payment_customer') ? 1 : 0}}"
                        :tickets="{{ json_encode($tickets, JSON_HEX_APOS) }}"
                        :booked_tickets="{{ json_encode($booked_tickets, JSON_HEX_APOS) }}"
                        :currency="{{ json_encode($currency, JSON_HEX_APOS) }}"
                        :total_capacity="{{ $total_capacity }}"
                        :date_format="{{ json_encode([
                            'vue_date_format' => format_js_date(),
                            'vue_time_format' => format_js_time()
                        ], JSON_HEX_APOS) }}"
                    >
                    </select-dates>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.INNER -->
    </div>
</section>
<!--SCHEDULE END-->

<!--Event FAQ-->
@if($event['faq'])
<section>
    <div id="lgx-about" class="lgx-about">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading">@lang('eventmie-pro::em.event_info')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-about-content-area text-center">
                            <div class="lgx-about-content">{!! $event['faq'] !!}</div>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
    </div>
</section>
@endif
<!--Event FAQ END-->

<!--TAGS-->
@php $i = 0; @endphp
@foreach($tag_groups as $key => $group)
@php $i++; @endphp
<section>
    <div id="lgx-schedule-tag" class="{{ ($i%2) ? 'lgx-schedule lgx-schedule-dark' : '' }}">
        <div class="lgx-inner" style="{{ ($i%2) ? 'background-image: url('.eventmie_asset('img/bg-pattern.png').');' : '' }}">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading {{ ($i%2) ? 'lgx-heading-white' : '' }}">
                            <h2 class="heading"> {{ ucfirst($key) }}</h2>
                        </div>
                    </div>
                </div>
                <!--//.ROW-->
                <div class="row justify-content-center">
                @foreach($group as $key1 => $value)
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="lgx-single-speaker">
                                
                                @if($value['is_page'] > 0)
                                <a href="{{ route('eventmie.events_tags',[$event->slug, str_replace(' ', '-', $value['title'])] ) }}">
                                @elseif($value['website'])
                                <a href="{{ $value['website'] }}" target="_blank">
                                @endif

                                    @if($value['image'])
                                    <img src="/storage/{{ $value['image'] }}" alt="{{ $value['title'] }}"/>
                                    @else
                                    <img src="{{ eventmie_asset('img/512x512.jpg') }}" alt="{{ $value['title'] }}"/>
                                    @endif

                                @if($value['is_page'] > 0 || $value['website'])
                                </a>
                                @endif
                            <figure>    
                                <figcaption>
                                    @if($value['is_page'] > 0)
                                    <div class="social-group">
                                        <a class="sp-tw" href="{{ $value['twitter'] }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                        <a class="sp-fb" href="{{ $value['facebook'] }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                        <a class="sp-insta" href="{{ $value['instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                        <a class="sp-in" href="{{ $value['linkedin'] }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                        <a class="sp-in" href="{{ $value['website'] }}" target="_blank"><i class="fas fa-globe"></i></a>
                                    </div>
                                    @endif

                                    <div class="speaker-info">
                                        <h3 class="title">
                                            @if($value['is_page'] > 0)
                                                <a href="{{ route('eventmie.events_tags',[$event->slug, str_replace(' ', '-', $value['title'])] ) }}">{{$value['title']}}</a>
                                            @elseif($value['website'])
                                                <a href="{{ $value['website'] }}" target="_blank">{{$value['title']}}</a>
                                            @else
                                                {{$value['title']}}
                                            @endif
                                        </h3>

                                        @if($value['sub_title'])
                                        <h4 class="subtitle">{{$value['sub_title']}}</h4>
                                        @endif
                                    </div>

                                </figcaption>

                            </figure>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.INNER -->
    </div>
</section>
@endforeach
<!--Tags END-->


<!--PHOTO GALLERY-->
@if(!empty($event->images))
<section>
    <div id="lgx-photo-gallery" class="lgx-gallery-popup lgx-photo-gallery-normal lgx-photo-gallery-black">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading lgx-heading-white">
                            <h2 class="heading">@lang('eventmie-pro::em.event_gallery')</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <gallery-images :gimages="{{ $event->images }}" ></gallery-images>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--PHOTO GALLERY END-->

<!--Event Video-->
@if(!empty($event->video_link))
<section>
    <div id="lgx-travelinfo" class="lgx-travelinfo">
        <div class="lgx-inner">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="lgx-heading">
                            <h2 class="heading">@lang('eventmie-pro::em.watch_trailer')</h2>
                        </div>
                    </div>
                    <!--//main COL-->
                </div>
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <iframe src="https://www.youtube.com/embed/{{ $event->video_link }}" allowfullscreen style="width: 100%; height: 500px; border-radius: 16px; border: none;"></iframe>
                    </div>
                </div>
                <!--//.ROW-->
            </div>
            <!-- //.CONTAINER -->
        </div>
    </div>
</section>
@endif
<!--Event Video END-->


<!--GOOGLE MAP-->
@if($event->latitude && $event->longitude)
<div class="innerpage-section g-map-wrapper">
    <div class="lgxmapcanvas map-canvas-default"> 
        
        <g-component :lat="{{ json_encode($event->latitude, JSON_HEX_APOS) }}" :lng="{{ json_encode($event->longitude, JSON_HEX_APOS) }}" >
        </g-component>

    </div>
</div>
@endif
<!--GOOGLE MAP END-->

@endsection

@section('javascript')
<script type="text/javascript" src="{{ eventmie_asset('js/events_show_v1.7.js') }}"></script>
<script type="text/javascript">
    var google_map_key = {!! json_encode( $google_map_key) !!};
    
</script>
@stop

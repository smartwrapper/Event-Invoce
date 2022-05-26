@extends('eventmie::layouts.app')

@section('title')
    @lang('eventmie-pro::em.mybookings')
@endsection

@section('content')

<main>
    <div class="lgx-post-wrapper">
        <section>
            <router-view :is_success="{{ json_encode($is_success, JSON_HEX_APOS) }}" ></router-view>
        </section>
    </div>
</main>
         
@endsection


@section('javascript')


<script>    
    var path = {!! json_encode($path, JSON_HEX_TAG) !!};
    var disable_booking_cancellation = {!! json_encode(setting('booking.disable_booking_cancellation'), JSON_HEX_TAG) !!};
    var hide_ticket_download = {!! json_encode(setting('booking.hide_ticket_download'), JSON_HEX_TAG) !!};
    var hide_google_calendar = {!! json_encode(setting('booking.hide_google_calendar'), JSON_HEX_TAG) !!};
</script>

<script type="text/javascript" src="{{ eventmie_asset('js/bookings_customer_v1.7.js') }}"></script>
@stop
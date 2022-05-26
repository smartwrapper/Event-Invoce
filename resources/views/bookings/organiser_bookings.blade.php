@extends('eventmie::layouts.app')

@section('title')
    @lang('eventmie-pro::em.organiser_bookings')
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
    var hide_ticket_download = {!! json_encode(setting('booking.hide_ticket_download'), JSON_HEX_TAG) !!};
</script>

<script type="text/javascript" src="{{ eventmie_asset('js/bookings_organiser_v1.7.js') }}"></script>
@stop
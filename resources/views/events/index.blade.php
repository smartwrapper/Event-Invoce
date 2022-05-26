@extends('eventmie::layouts.app')

{{-- Page title --}}
@section('title')
    @lang('eventmie-pro::em.events')
@endsection

@section('content')

<main>
    <div class="lgx-page-wrapper">
        <router-view :date_format="{{ json_encode([
            'vue_date_format' => format_js_date(),
            'vue_time_format' => format_js_time()
        ], JSON_HEX_APOS) }}">
        </router-view>
    </div>
</main>
@endsection

@section('javascript')

<script>    
    var path           = {!! json_encode($path, JSON_HEX_TAG) !!};
</script>
<script type="text/javascript" src="{{ eventmie_asset('js/events_listing_v1.7.js') }}"></script>
@stop
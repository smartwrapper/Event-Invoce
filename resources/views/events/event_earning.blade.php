@extends('eventmie::layouts.app')

@section('title')
    @lang('eventmie-pro::em.manage_earning')
@endsection

@section('content')

<main>
    <div class="lgx-post-wrapper">
        <section>
            <router-view></router-view>
        </section>
    </div>
</main>
         
@endsection


@section('javascript')


<script>    
        var path = {!! json_encode($path, JSON_HEX_TAG) !!};
</script>

<script type="text/javascript" src="{{ eventmie_asset('js/event_earning_v1.7.js') }}"></script>
@stop
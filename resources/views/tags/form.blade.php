@extends('eventmie::layouts.app')

{{-- Page title --}}
@section('title')
    @lang('eventmie-pro::em.manage_tags')
@endsection

    
@section('content')

<main>
    <div class="lgx-post-wrapper">
        <section>
            <router-view
                :organiser_id="{{ $organiser_id }}"
            ></router-view> 
        </section>
    </div>
</main>

@endsection

@section('javascript')

<script>    
    var path = {!! json_encode($path, JSON_HEX_TAG) !!};
</script>

<script type="text/javascript" src="{{ eventmie_asset('js/tags_manage_v1.7.js') }}"></script>
@stop

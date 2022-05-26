@extends('eventmie::layouts.app')

{{-- Page title --}}
@section('title')
    @lang('eventmie-pro::em.scan_ticket')
@endsection

    
@section('content')

<main>
    <!--SCHEDULE-->
    <div class="lgx-post-wrapper">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (session('status'))
                        <div class="alert alert-success">
                            {{session('status')}}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-3 col-md-6">

                        <ticket-scan :date_format="{{ json_encode([
                            'vue_date_format' => format_js_date(),
                            'vue_time_format' => format_js_time()
                        ], JSON_HEX_APOS) }}" ></ticket-scan>
                        
                        <form id="form" action="{{route('eventmie.verify_ticket')}}"  method="POST" enctype="multipart/form-data" class="lgx-contactform">
                            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                            <input type="hidden" name="booking_id" id="booking_id">
                            <input type="hidden" name="order_number" id="order_number" >
                            
                            <button type="submit" id="check_in_button" class="btn lgx-btn btn-block lgx-btn-success" style="display: none;">@lang('eventmie-pro::em.verify_n_checkin')</button>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <!--SCHEDULE END-->
</main>

@endsection

@section('javascript')
<script type="text/javascript" src="{{ eventmie_asset('js/ticket_scan_v1.7.js') }}"></script>


@stop

@extends('eventmie::layouts.app')

@section('title')
    @lang('eventmie-pro::em.contact')
@endsection
    
@section('content')

    <main>
        <div class="lgx-page-wrapper">
            <!--News-->
            <section>
                <div class="container">
                    <div class="row">

                        <div class="col-12 offset-sm-2 col-sm-8 offset-lg-3 col-lg-6">
                            @if (\Session::has('msg'))
                                <div class="alert alert-success">
                                    {{ \Session::get('msg') }}
                                </div>
                            @endif
                            <form method="POST" class="lgx-contactform" action="{{route('eventmie.store_contact')}}">
                                @csrf
                                @honeypot
                                
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control lgxname" id="lgxname" placeholder="@lang('eventmie-pro::em.name')" required>
                                    
                                    @if ($errors->has('name'))
                                        <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="email" name="email" class="form-control lgxemail" id="lgxemail" required placeholder="@lang('eventmie-pro::em.email')">
                                    
                                    @if ($errors->has('email'))
                                        <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <input type="text" name="title" class="form-control lgxsubject" id="lgxsubject" required placeholder="@lang('eventmie-pro::em.title')">
                                    
                                    @if ($errors->has('title'))
                                        <div class="alert alert-danger">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <textarea class="form-control lgxmessage" name="message" id="lgxmessage" rows="5" required placeholder="@lang('eventmie-pro::em.message')"></textarea>
                                    
                                    @if ($errors->has('message'))
                                        <div class="alert alert-danger">{{ $errors->first('message') }}</div>
                                    @endif
                                </div>


                                <button type="submit"  value="contact-form" class="lgx-btn lgxsend lgx-send btn-block"><span><i class="fas fa-paper-plane"></i> @lang('eventmie-pro::em.send_message')</span></button>
                            </form>

                        </div> <!--//.COL-->
                    </div>
                </div><!-- //.CONTAINER -->
            </section>
            <!--News END-->
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <g-component 
                        :lat="{{ json_encode(setting('contact.google_map_lat'), JSON_HEX_APOS) }}" 
                        :lng="{{ json_encode(setting('contact.google_map_long'), JSON_HEX_APOS) }}" 
                    >
                    </g-component>
                </div>
            </div>    
        </div>
    </main>

    

@endsection

@section('javascript')


<script type="text/javascript" src="{{ eventmie_asset('js/events_show_v1.7.js') }}"></script>

<script type="text/javascript">
    var google_map_key = {!! json_encode( setting('apps.google_map_key')) !!};

</script>

@stop
@extends('eventmie::layouts.app')

@section('title')
    @lang('eventmie-pro::em.blogs')
@endsection

@section('content')

<main>
    <div class="lgx-page-wrapper">
        <!--Blogs-->
        <section>
            <div class="container">
                <div class="row">
                    @if(!empty($posts))
                        @foreach ($posts as $item)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="lgx-single-news">
                                <figure>
                                    <a href="{{route('eventmie.post_view', $item['slug'])}}">
                                        <img src="/storage/{{ $item['image'] }}" alt="">
                                    </a>
                                </figure>
                                
                                <div class="single-news-info">
                                    <div class="meta-wrapper hidden">
                                        <span>{{\Carbon\Carbon::parse($item['updated_at'])->translatedFormat(format_carbon_date())}}</span>
                                    </div>
                                
                                    <h3 class="title">
                                        <a href="{{route('eventmie.post_view', $item['slug'])}}">{{$item['title']}}</a>
                                    </h3>

                                    <div class="meta-wrapper">
                                        <span>{{ $item['excerpt'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>        
                        @endforeach
                    @else
                    <div class="col-md-12">
                        <h4 class="text-center">@lang('eventmie-pro::em.nothing')</h4>
                    </div>
                    @endif
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 text-center">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </section>
        <!--Blogs END-->
    </div>
</main>
@endsection
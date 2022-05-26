@extends('eventmie::layouts.app')

@section('title')
    {{ ucfirst($tag->type) }} - {{ $tag->title }}
@endsection

@section('content')
<main>
    <div class="lgx-post-wrapper">
         <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-5 col-md-4">
                        <article>
                            <header>
                                
                                <figure>
                                    @if($tag->image)
                                    <img src="/storage/{{ $tag->image }}" alt="{{ $tag->title }}" class="img-responsive img-rounded"/>
                                    @else
                                    <img src="{{ eventmie_asset('img/512x512.jpg') }}" alt="{{ $tag->title }}" class="img-responsive img-rounded" />
                                    @endif
                                </figure>
                                <div class="text-area">
                                    <div class="speaker-info">
                                        <h1 class="title">{{ $tag->title }}</h1>
                                        <h4 class="subtitle">{{ $tag->sub_title }}</h4>
                                    </div>
                                    <ul class="list-inline lgx-social">
                                        @if($tag->twitter)
                                        <li><a href="{{ $tag->twitter }}" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                                        @endif
                                        
                                        @if($tag->facebook)
                                        <li><a href="{{ $tag->facebook }}" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                                        @endif

                                        @if($tag->linkedin)
                                        <li><a href="{{ $tag->linkedin }}" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                                        @endif
                                        
                                        @if($tag->instagram)
                                        <li><a href="{{ $tag->instagram }}" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                                        @endif

                                        @if($tag->website)
                                        <li><a href="{{ $tag->website }}" target="_blank"><i class="fas fa-globe" aria-hidden="true"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </header>
                        </article>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-md-8">
                        <article>
                            <section>{!! $tag->description !!}</section>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

@endsection
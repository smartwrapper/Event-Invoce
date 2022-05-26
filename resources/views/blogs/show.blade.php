@extends('eventmie::layouts.app')

@section('title', $post['title'])
@section('meta_title', $post['seo_title'])
@section('meta_keywords', $post['meta_keywords'])
@section('meta_description', $post['meta_description'])
@section('meta_image', '/storage/'.$post['image'])
@section('meta_url', url()->current())

@section('content')
<main>
    <div class="lgx-post-wrapper">
        <!--News-->
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <article>
                            <header>
                                <figure>
                                    <img src="/storage/{{ $post['image'] }}" alt="{{ $post['title'] }}" class="img-rounded"/>
                                </figure>
                                <div class="text-area">
                                    <div class="hits-area">
                                        <div class="date">
                                            <p><i class="fas fa-history"></i> {{ \Carbon\Carbon::parse($post['updated_at'])->translatedFormat(format_carbon_date()) }}</p>
                                        </div>
                                    </div>
                                    <h1 class="title">{{$post['title']}}</h1>
                                </div>
                            </header>
                            <section>
                                {!! $post['body'] !!}
                            </section>
                        </article>
                    </div>
                </div>
            </div><!-- //.CONTAINER -->
        </section>
        <!--News END-->
    </div>
</main>

@endsection
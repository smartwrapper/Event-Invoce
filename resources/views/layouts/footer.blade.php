<!--FOOTER-->
<footer>
    <div id="lgx-footer" class="lgx-footer-black"> <!--lgx-footer-white-->
        <div class="lgx-inner-footer">
            <div class="container-fluid">
                
                <div class="lgx-footer-area footer-custom-menu">
                    <div class="lgx-footer-single footer-brand">
                        <img class="footer-brand-logo" src="/storage/{{ setting('site.logo') }}" alt="{{ setting('site.site_name') }}" />
                        <p class="footer-brand-name">{{ setting('site.site_name') }}</p>
                        <p class="footer-brand-slogan">{{ setting('site.site_slogan') }}</p>
                    </div>

                    <div class="lgx-footer-single">
                        <h3 class="footer-title">@lang('eventmie-pro::em.useful_links')</h3>
                        <ul class="list-unstyled">
                            <li><a class="col-grey" href="{{ route('eventmie.page', ['page' => 'about']) }}">@lang('eventmie-pro::em.about')</a></li>
                            <li><a class="col-grey" href="{{ route('eventmie.events_index') }}">@lang('eventmie-pro::em.events')</a></li>
                            <li><a class="col-grey" href="{{ route('eventmie.get_posts') }}">@lang('eventmie-pro::em.blogs')</a></li>
                            <li><a class="col-grey" href="{{ route('eventmie.page', ['page' => 'terms']) }}">@lang('eventmie-pro::em.terms')</a></li>
                            <li><a class="col-grey" href="{{ route('eventmie.page', ['page' => 'privacy']) }}">@lang('eventmie-pro::em.privacy')</a></li>
                        </ul>
                    </div>
                    <div class="lgx-footer-single">
                        <h3 class="footer-title">@lang('eventmie-pro::em.contact')</h3>
                        <a href="{{ route('eventmie.contact') }}">
                            <h4 class="date">@lang('eventmie-pro::em.contact_send_message')</h4>
                        </a>
                        <address>{{ setting('contact.address') }}</address>
                        <p class="text"><i class="fas fa-phone-alt"></i> {{ setting('contact.phone') }}</p>
                        <p class="text"><i class="fas fa-envelope"></i> {{ setting('contact.email') }}</p>
                        
                        <a href="{{ route('eventmie.contact') }}" class="map-link">
                            <i class="fas fa-map-marked-alt" aria-hidden="true"></i> 
                            @lang('eventmie-pro::em.contact_find_us')
                        </a>
                    </div>
                    <div class="lgx-footer-single">
                        <h3 class="footer-title">@lang('eventmie-pro::em.social')</h3>
                        <p class="text">@lang('eventmie-pro::em.social_find')</p>
                        <ul class="list-inline lgx-social-footer">
                            @if(setting('social.facebook'))
                            <li><a href="{{ 'https://www.facebook.com/'.setting('social.facebook') }}" target="_blank"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                            @endif

                            @if(setting('social.twitter'))
                            <li><a href="{{ 'https://twitter.com/'.setting('social.twitter') }}" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                            @endif
                            
                            @if(setting('social.instagram'))
                            <li><a href="{{ setting('social.instagram') }}" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                            @endif
                            
                            @if(setting('social.linkedin'))
                            <li><a href="{{ setting('social.linkedin') }}" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                            @endif
                        </ul>
                    </div>
                  
                </div>


                {{-- Admin footer menu items --}}
                @php $footerMenuItems = footerMenu() @endphp
                @if(!empty($footerMenuItems))
                <div class="lgx-footer-area footer-custom-menu">
                    <div class="lgx-footer-single"></div>
                    
                    @php $key = 1; @endphp
                    @foreach($footerMenuItems as $parentItem)

                    <div class="lgx-footer-single">
                        <h3 class="footer-title"><i class="{{ $parentItem->icon_class }}"></i> {{ $parentItem->title }}</h3>
                        <ul class="list-unstyled">
                            @foreach($parentItem->submenu as $childItem)
                            <li>
                                <a class="col-grey" target="{{ $childItem->target }}" href="{{ $childItem->url }}">
                                    <i class="{{ $childItem->icon_class }}"></i> {{ $childItem->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(!($key%3))
                    <div class="flex-break"></div>
                    <div class="lgx-footer-single"></div>
                    @endif

                    @php $key++; @endphp

                    @endforeach
                </div>
                @endif

                <div class="lgx-footer-bottom lgx-footer-single pb-4">
                    <ul class="list-inline">
                        @foreach(lang_selector() as $val)
                        <li class="list-inline-item border-seperator">
                            <a class="col-grey {{ $val == config('app.locale') ? 'active' : '' }}" href="{{ route('eventmie.change_lang', ['lang' => $val]) }}">@lang('eventmie-pro::em.lang_'.$val)</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="lgx-footer-bottom">
                    <div class="lgx-copyright">
                        <p> 
                            <span>©</span> {{ date('Y') }} 
                            <a href="{{ eventmie_url() }}">{{ (setting('site.site_name') ? setting('site.site_name') : config('app.name')) }}</a><br>

                            @if(!empty(setting('site.site_footer'))) 
                            {!! setting('site.site_footer') !!}
                            @endif
                        </p>
                    </div>
                </div>

            </div>
            <!-- //.CONTAINER -->
        </div>
        <!-- //.footer Middle -->
    </div>
</footer>
<!--FOOTER END-->
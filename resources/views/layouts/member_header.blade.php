{{-- Common between Admin, Customer & Organiser --}}
<li>
    @php
        $data  = notifications();
    @endphp

    <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
        <i class="fas fa-bell"> </i> 
        <span class="badge bg-red">{{$data['total_notify']}}</span> 
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        @if(!empty($data['notifications']))      
            @foreach ($data['notifications'] as $notification) 
            <li>
                <a class="dropdown-item" href="{{route('eventmie.notify_read', [$notification->n_type])}}"> 
                    {{ $notification->total }} 
                    @if($notification->n_type == 'user')
                        @lang('eventmie-pro::em.user')
                    @elseif($notification->n_type == 'cancel')
                        @lang('eventmie-pro::em.booking_cancellation')
                    @elseif($notification->n_type == 'review')
                        @lang('eventmie-pro::em.show_reviews')
                    @elseif($notification->n_type == 'contact')
                        @lang('eventmie-pro::em.contact')
                    @elseif($notification->n_type == 'events')
                        @lang('eventmie-pro::em.event')
                    @elseif($notification->n_type == 'Approve-Organizer')
                        @lang('eventmie-pro::em.requested_to_become_organiser')
                    @elseif($notification->n_type == 'Approved-Organizer')
                        @lang('eventmie-pro::em.became_organiser_successful')
                    @elseif($notification->n_type == 'bookings')
                        @lang('eventmie-pro::em.booking')
                    @elseif($notification->n_type == 'forgot_password')
                        @lang('eventmie-pro::em.reset_password')
                    @endif
                </a>
            </li>
            @endforeach
        @else
        <li>
            <a class="dropdown-item" > @lang('eventmie-pro::em.no_notifications')</a>
        </li>
        @endif
    </ul>
</li>


<li>
    <a id="navbarDropdown" class="dropdown-toggle active" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
        @if(Auth::user()->hasRole('customer'))
        <i class="fas fa-user-circle"></i> 
        @elseif(Auth::user()->hasRole('organiser'))
        <i class="fas fa-person-booth"></i> 
        @else
        <i class="fas fa-user-shield"></i> 
        @endif

        {{ Auth::user()->name }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu multi-level">

        {{-- Customer --}}
        @if(Auth::user()->hasRole('customer'))
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.mybookings_index') }}"><i class="fas fa-money-check-alt"></i> @lang('eventmie-pro::em.mybookings')</a>
        </li>
        @endif

        {{-- Organiser --}}
        @if(Auth::user()->hasRole('organiser'))
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.organizer_dashboard') }}"><i class="fas fa-tachometer-alt"></i> @lang('eventmie-pro::em.dashboard')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.myevents_index') }}"><i class="fas fa-calendar-alt"></i> @lang('eventmie-pro::em.manage_events')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.obookings_index') }}"><i class="fas fa-money-check-alt"></i> @lang('eventmie-pro::em.manage_bookings')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.event_earning_index') }}"><i class="fas fa-wallet"></i> @lang('eventmie-pro::em.manage_earning')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.tags_form') }}"><i class="fas fa-user-tag"></i> @lang('eventmie-pro::em.manage_tags')</a>
        </li>
        @endif

        {{-- Admin --}}
        @if(Auth::user()->hasRole('admin'))
        <li>
            <a class="dropdown-item" href="{{ eventmie_url().'/'.config('eventmie.route.admin_prefix') }}">
            <i class="fas fa-tachometer-alt"></i>  @lang('eventmie-pro::em.admin_panel')</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('eventmie.profile') }}"><i class="fas fa-id-card"></i> @lang('eventmie-pro::em.profile')</a>
        </li>
        @endif

        <li>
            <a class="dropdown-item" href="{{ route('eventmie.logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> @lang('eventmie-pro::em.logout')
            </a>
            <form id="logout-form" action="{{ route('eventmie.logout') }}" method="POST" style="display: none;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </li>
    </ul>
</li>

{{-- Admin --}}
@if(Auth::user()->hasRole('admin'))
<li>
    <a class="lgx-scroll" href="{{ route('eventmie.ticket_scan') }}">
    <i class="fas fa-qrcode"></i> @lang('eventmie-pro::em.scan_ticket')</a>
</li>
<li>
    <a class="lgx-scroll" href="{{ route('eventmie.myevents_form') }}">
    <i class="fas fa-calendar-plus"></i> @lang('eventmie-pro::em.create_event')</a>
</li>
@endif

{{-- Organiser --}}
@if(Auth::user()->hasRole('organiser'))
<li>
    <a class="lgx-scroll" href="{{ route('eventmie.ticket_scan') }}">
    <i class="fas fa-qrcode"></i> @lang('eventmie-pro::em.scan_ticket')</a>
</li>
<li>
    <a class="lgx-scroll" href="{{ route('eventmie.myevents_form') }}">
    <i class="fas fa-calendar-plus"></i> @lang('eventmie-pro::em.create_event')</a>
</li>
@endif


{{-- Customer --}}
@if(Auth::user()->hasRole('customer'))
<li>
    <a class="lgx-scroll" href="{{ route('eventmie.mybookings_index') }}">
    <i class="fas fa-money-check-alt"></i> @lang('eventmie-pro::em.mybookings')</a>
</li>
@endif
@component('mail::message')
# {{ $mail_data->mail_subject }}

<br>

**{{ __('eventmie-pro::em.event') }}:** {{ $mail_data->mail_data[0]['event_title'] }}<br>

**{{ __('eventmie-pro::em.date') }}:** {{ userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(true))  }} - {{ ( userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', 'Y-m-d') <= userTimezone($mail_data->mail_data[0]['event_end_date'].' '.$mail_data->mail_data[0]['event_end_time'], 'Y-m-d H:i:s', 'Y-m-d') ? userTimezone($mail_data->mail_data[0]['event_end_date'].' '.$mail_data->mail_data[0]['event_end_time'], 'Y-m-d H:i:s', format_carbon_date(true)) : userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(true)) ) }} {{ '('. showTimezone() .')' }} <br>

**{{ __('eventmie-pro::em.timings') }}:** {{ userTimezone($mail_data->mail_data[0]['event_start_date'].' '.$mail_data->mail_data[0]['event_start_time'], 'Y-m-d H:i:s', format_carbon_date(false))  }} - {{ userTimezone($mail_data->mail_data[0]['event_end_date'].' '.$mail_data->mail_data[0]['event_end_time'], 'Y-m-d H:i:s', format_carbon_date(false)) }} {{ '('. showTimezone() .')' }}  <br>

<br>

## {{ __('eventmie-pro::em.tickets') }} 


@component('mail::table')
| {{ __('eventmie-pro::em.order') }} | {{ __('eventmie-pro::em.ticket') }} | {{ __('eventmie-pro::em.price') }} | {{ __('eventmie-pro::em.quantity') }} |
|:-------------:|:-------------:|:-------------:|:-------------:|
@foreach($mail_data->mail_data as $val)
| {{$val['order_number']}} | {{$val['ticket_title']}} | {{$val['ticket_price']}} {{$val['currency']}} | {{$val['quantity']}} |
@endforeach
@endcomponent


@component('mail::button', ['url' => $mail_data->action_url])
{{ $mail_data->action_title }}
@endcomponent

@if($mail_data->is_online)
@component('mail::button', ['url' => $mail_data->action_url])
{{ __('eventmie-pro::em.online_event') }}
@endcomponent
@endif

{!! __('eventmie-pro::em.order_terms') !!}<br><br>

{{ __('eventmie-pro::em.thank_you') }}<br>
{{ (setting('site.site_name') ? setting('site.site_name') : config('app.name')) }} - [{{ trim(eventmie_url(), '/') }}]({{ eventmie_url() }})
@endcomponent

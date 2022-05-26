@component('mail::message')
# {{ $mail_data->mail_subject }}

{{ $mail_data->mail_message }}

@foreach($extra_lines as $val)
- {{ $val }}
@endforeach


@component('mail::button', ['url' => $mail_data->action_url])
{{ $mail_data->action_title }}
@endcomponent

{{ __('eventmie-pro::em.thank_you') }}<br>
{{ (setting('site.site_name') ? setting('site.site_name') : config('app.name')) }} - [{{ trim(eventmie_url(), '/') }}]({{ eventmie_url() }})
@endcomponent

<x-mail::message>
<h1>Hi {{ $name }}</h1>

<p>You have started {{ $plan }}. Here is the information you can use for activation.</p>
<x-mail::panel>
<p><b>License Key:</b> {{ $key }}</p>
</x-mail::panel>
<br>
<p>This key can only be used for one email.</p>
<br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

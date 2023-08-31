<x-mail::message>
<h1>Hi Admin!</h1>

<p><b>{{ $name }}</b> has contacted you through {{ config('app.name') }}.</p>
<x-mail::panel>
<p><b>Name:</b> {{ $name }}</p>
<p><b>Email:</b> {{ $email }}</p>
<p class="mb-0"><b>Message:</b></p>
@foreach (explode("\n", $message) as $msg)
<p class="mb-0">{{ $msg }}</p>
@endforeach
</x-mail::panel>
<br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

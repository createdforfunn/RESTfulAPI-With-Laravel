{{--Hello {{$user->name}} !--}}
{{--Thank you for creating an account. Please verify your email using this link:--}}
{{--{{route('verify', $user->verification_token)}}   --}}{{--creating a link--}}




@component('mail::message')
    # Hello {{$user->name}}
    Thank you for creating an account. Please verify your email using this button:
    @component('mail::button', ['url' => route('verify', $user->verification_token)])
        Verify Account
    @endcomponent
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
@extends('layouts.mail.app')

@section('title', 'EMAIL VERIFICATION')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/inbox.png') }}" alt="Verification Email" />
            <h3 class="confirmation-text">EMAIL VERIFICATION</h3>
        </div>

        <p class="greeting-text">Hello {{ $data['name'] }}</p>
        <p class="thank-you-text">
            Thank you for signing up on Ogun State Tourism Center. Please verify your email address to get started.
        </p>

        <div class="items-ordered-area" style="margin-top:0px;">
            <div class="my-4 track-order bg-white">
                <p class="font-weight-bold bg-white mb-3">
                    To activate your account, please click the button below.
                </p>
                <a href="{{ url(env('CLIENT_BASE_URL') . 'auth/email-verified/' . $data['verification_code']) }}"
                    class="track-order-btn">Verify Email</a>
            </div>
        </div>
    </div>
    <hr class="my-3" />
@endsection

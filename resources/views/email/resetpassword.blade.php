@extends('layouts.mail.app')

@section('title', 'RESET PASSWORD')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/reset-password.png') }}" alt="Order Confirmation" />
            <h3 class="confirmation-text">RESET PASSWORD</h3>
        </div>

        <p class="greeting-text">Hello</p>
        <p class="thank-you-text">
            You are receiving this email because we received a password reset request for your account.
        </p>

        <div class="items-ordered-area" style="margin-top:0px;">
            <div class="my-4 track-order bg-white">
                <p class="font-weight-bold bg-white mb-3">
                    Click on the button below to reset your password
                </p>
                <a href="{{ url(env('CLIENT_BASE_URL') . 'auth/create-new-password/' . $data['verification_code'] . '?email=' . $data['email']) }}"
                    class="track-order-btn">Reset Password</a>
            </div>
            <br>
            <p>If you did not request a password reset, no further action is required.</p>
        </div>
    </div>
    <hr class="my-3" />
@endsection

@extends('layouts.mail.app')

@section('title', 'ADMIN INVITE')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/inbox.png') }}" alt="Verification Email" />
            <h3 class="confirmation-text">OGUN STATE TOURISIM INVITATION</h3>
        </div>

        <p class="greeting-text">Hello {{ $data['firstname'] . ' ' . $data['lastname'] }}</p>
        <p class="thank-you-text">
            You have been invited to join Ogun state tourism as an admin.
        </p>

        <p class="thank-you-text">
            Please find below your login credentials <br /> <br />
            <b>Email and Username</b> <br />
            {{ $data['email'] }}
            <br />
            <b>Password</b><br />
            {{ $data['password'] }}
        </p>


        <div class="items-ordered-area" style="margin-top:0px;">
            <div class="my-4 track-order bg-white">
                <p class="font-weight-bold bg-white mb-3">
                    To get started, please click the button below.
                </p>
                {{-- <a href="{{ env('CLIENT_BASE_URL') . 'auth/email-verified/' . $data['verification_code'] }}"
                    class="track-order-btn">Verify Email</a> --}}
            </div>
        </div>
    </div>
    <hr class="my-3" />
@endsection

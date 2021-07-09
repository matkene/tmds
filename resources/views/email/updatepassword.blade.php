@extends('layouts.mail.app')

@section('title', 'PASSWORD UPDATED')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/checked.png') }}" alt="Order Confirmation" />
            <h3 class="confirmation-text">PASSWORD CHANGED</h3>
        </div>

        <p class="greeting-text">Hello {{ $data['name'] }}</p>
        <p class="thank-you-text">
            Your Password on Hire and Pass was Updated Successfully! Kindly ensure that you do not share your credentials
            with a third party.
        </p>
    </div>
    <hr class="my-3" />
@endsection

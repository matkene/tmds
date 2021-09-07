@extends('layouts.mail.app')

@section('title', 'ORDER SHIPPED')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/checked.png') }}" alt="Order Confirmation" />
            <h3 class="confirmation-text">BOOKING RECEIPT</h3>
        </div>

        <div class="order-text">
            <h4>TICKET NO '#'{{ $data['ticketNo'] }}</h4>
            {{-- <p class="mb-0 text-color">{{ $data['orderdetails']['created_at']->format('M d, Y') }}</p> --}}
        </div>

        <p class="greeting-text">Hi, {{ $data['fullname'] }}</p>
        <p class="thank-you-text">
            This is to inform you that your ticket has been booked for {{ $data['dateOfVisit'] }}.
        </p>

        <div class="items-ordered-area">
            <div class="topic">
                <p>Please see the details below</p>
            </div>

            <hr class="my-3" />

            <p class="greeting-text">No of Adult Male</p>
            <p class="thank-you-text">
                {{ $data['adultMale'] }}
            </p>

            <p class="greeting-text">No of Adult Female</p>
            <p class="thank-you-text">
                {{ $data['adultFemale'] }}
            </p>

            <p class="greeting-text">No of Adult Male</p>
            <p class="thank-you-text">
                {{ $data['adultMale'] }}
            </p>

            <p class="greeting-text">No of Children Male</p>
            <p class="thank-you-text">
                {{ $data['childrenMale'] }}
            </p>

            <p class="greeting-text">No of Children Female</p>
            <p class="thank-you-text">
                {{ $data['childrenFemale'] }}
            </p>

            <p class="greeting-text">No of Infant Male</p>
            <p class="thank-you-text">
                {{ $data['infantMale'] }}
            </p>

            <p class="greeting-text">No of Infant Female</p>
            <p class="thank-you-text">
                {{ $data['infantFemale'] }}
            </p>

            <p class="greeting-text">Total Visit</p>
            <p class="thank-you-text">
                {{ $data['totalVisitor'] }}
            </p>

            <p class="greeting-text">Date of Visit</p>
            <p class="thank-you-text">
                {{ $data['dateOfVisit'] }}
            </p>

            <hr>
            <p class="greeting-text">Total</p>
            <h3 class="thank-you-text">
                {{ $data['total'] }}
            </h3>
        </div>
    </div>
@endsection

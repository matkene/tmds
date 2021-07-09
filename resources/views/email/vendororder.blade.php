@extends('layouts.mail.app')

@section('title', 'ORDER CONFIRMATION')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/checked.png') }}" alt="Order Confirmation" />
            <h3 class="confirmation-text">ORDER CONFIRMATION</h3>
        </div>

        <div class="order-text">
            <h4>ORDER #{{ $data['orderID'] }}</h4>
            {{-- <p class="mb-0 text-color">{{ $data['orderdetails']['created_at']->format('M d, Y') }}</p> --}}
        </div>

        <p class="greeting-text">Hi, {{ $data['name'] }}</p>
        <p class="thank-you-text">
            You have a new Order on Aduaba Online Store Vendor Portal.
        </p>

        <div class="items-ordered-area">
            <div class="topic">
                <p>ITEMS ORDERED</p>
            </div>

            <hr class="my-3" />

            <table>
                <colgroup span="3"></colgroup>
                <tr>
                    <th class="img"></th>
                    <th class="product-details"></th>
                    <th class="price"></th>
                </tr>
                @php
                    
                    $subtotal = $data['orderdetails']['quantity_ordered'] * $data['orderdetails']['unit_price'];
                    
                    $imageUrl = explode('|', $data['orderdetails']['product']['product_image'])[0];
                @endphp

                <tr class="product-table">
                    <td class="img-column">
                        <img class="img-fluid" src="{{ $imageUrl }}" alt="flower" />
                    </td>
                    <td class="product-details-column">
                        <p class="text-color product-price font-weight-bold">
                            ₵{{ number_format($data['orderdetails']['unit_price'], 2) }}</p>
                        <p class="product-name">{{ $data['orderdetails']['product_name'] }}</p>
                        <p class="product-qty">Quantity: {{ $data['orderdetails']['quantity_ordered'] }}</p>
                    </td>
                    <td class="price-column font-weight-bold text-right">
                        ₵{{ number_format($data['orderdetails']['quantity_ordered'] * $data['orderdetails']['unit_price'], 2) }}
                    </td>
                </tr>
                <tr class="product-table">
                    <td class="img-column">
                        <p class="font-weight-bold my-3 bg-white">Subtotal</p>
                    </td>
                    <td class="product-details-column my-3"></td>
                    <td class="price-column my-3">
                        <p class="bg-white text-right">₵{{ number_format($subtotal, 2) }}</p>
                    </td>
                </tr>

                <tr class="product-table">
                    <td class="img-column">
                        <p class="font-weight-bold my-4 bg-white">Order Total</p>
                    </td>
                    <td class="product-details-column my-4"></td>
                    <td class="price-column my-4">
                        <h3 class="font-weight-bold bg-white text-right">
                            ₵{{ number_format($data['orderdetails']['quantity_ordered'] * $data['orderdetails']['unit_price'] + $data['orderdetails']['shipping_fee'], 2) }}
                        </h3>
                    </td>
                </tr>
            </table>

            <div class="delivery-details">
                <div class="delivery-left">
                    <h5 class="font-weight-bold">Delivery Address</h5>
                    <p>{{ $data['address'] }}</p>
                    {{-- <p>Lagos</p> --}}
                </div>
                <div class="date-right">
                    <h5 class="font-weight-bold text-right">
                        Customer Contact
                    </h5>
                    <p class="text-right">{{ $data['phoneno'] }}</p>
                    <p class="text-right">{{ $data['useremail'] }}</p>
                </div>
                <div class="bg-white clear"></div>
            </div>

            {{-- <div class="my-4 track-order bg-white">
            <p class="font-weight-bold bg-white">
                You can follow the status of your order by clicking the button
                below
            </p>

            <button class="track-order-btn">View order Status</button>
        </div> --}}
        </div>
    </div>
@endsection

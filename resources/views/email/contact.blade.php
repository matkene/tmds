@extends('layouts.mail.app')

@section('title', 'CONTACT US')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/contact-us.png') }}" alt="Order Confirmation" />
            <h3 class="confirmation-text">CONTACT US</h3>
        </div>

        <p class="greeting-text">Hello Admin!</p>
        <p class="thank-you-text">
            New Message from {{ $data['fullname'] }}
        </p>

        <div class="items-ordered-area" style="margin-top:0px;">
            <table>
                <tr class="product-table">
                    <td class="img-column">
                        <p class=" my-4 bg-white">
                            Message: {{ $data['message'] }} <br>
                            Email: {{ $data['email'] }} <br>
                            Phone No: {{ $data['phoneno'] }}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr class="my-3" />
@endsection

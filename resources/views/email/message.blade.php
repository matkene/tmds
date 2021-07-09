@extends('layouts.mail.app')

@section('title', 'MESSAGE')

@section('content')
    <div class="card-text-area">
        <div class="verified-logo-holder">
            <img class="verified-logo" src="{{ asset('images/inbox.png') }}" alt="Order Confirmation" />
            <h3 class="confirmation-text">MESSAGE</h3>
        </div>

        <p class="greeting-text">Hello {{ auth()->user()->firstname }}, </p>
        <p class="thank-you-text">
           You have a New Message from {{ $data['fullname'] }} on your All Things Africa account.
        </p>

        <div class="items-ordered-area" style="margin-top:0px;">
            <table>
                <tr class="product-table">
                    <td class="img-column">
                        <p class=" my-4 bg-white">
                            Message: {{ $data['message'] }} <br>
                            Email: {{ $data['useremail'] }}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr class="my-3" />
@endsection

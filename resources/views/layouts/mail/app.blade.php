<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@200;400&display=swap" rel="stylesheet" />
    <title>@yield('title')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Mukta', sans-serif;
        }

        body {
            background: lightgray;
        }

        .container {
            max-width: 50%;
            padding: 1rem;
            margin: auto;
        }

        .card {
            border: none;
            background-color: #fff !important;
            max-width: 600px;
            height: auto;
            padding-bottom: 2rem;
        }

        .card-header {
            background-color: rgba(58, 149, 60, 0.1);
            height: 132px;
            width: 100%;
            text-align: center;
        }

        .logo-img {
            background-color: rgba(58, 149, 60, 0.01);
            margin-top: 25px;
        }

        .card-text-area {
            padding: 2rem;
            background-color: #fff;
        }

        .verified-logo-holder {
            text-align: center;
            background-color: #fff;
            margin-bottom: 48px;
        }

        .text-color {
            color: #3a953c;
        }

        .verified-logo {
            margin-bottom: 10px;
            background-color: #fff;
        }

        .confirmation-text {
            font-weight: bold;
            background-color: #fff;
            font-size: 24px;
            font-style: normal;
            line-height: 29px;
            color: #3a953c;
        }

        .order-text {
            margin-bottom: 30px;
        }

        .order-text,
        .order-text h4,
        .order-text p {
            background-color: #fff;
        }

        .order-text h4 {
            margin-bottom: 14px;
        }

        .order-text p {
            font-weight: bold;
        }

        .greeting-text {
            font-size: 17px;
            font-weight: bold;
            background-color: #fff;
            margin-bottom: 20px;
        }

        .thank-you-text {
            background-color: #fff;
        }

        .items-ordered-area {
            background-color: #fff;
            margin-top: 42px;
        }

        .items-ordered-area .topic p {
            font-weight: 500;
            background-color: #fff;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            background-color: #fff;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        table th .product-details {
            width: 60%;
            background-color: #fff;
        }

        td,
        tr,
        th {
            background-color: #fff;
        }

        .product-price,
        .product-name {
            font-weight: bold;
            background-color: #fff;
        }

        .product-price {
            margin-bottom: 12px;
            background-color: #fff;
        }

        .product-qty {
            color: #999;
            font-size: 14px;
            margin-top: 10px;
            background-color: #fff;
        }

        .img-column {
            width: 20%;
        }

        .text-dark {
            text-decoration: none;
            color: black;
        }

        hr {
            background-color: #ccc;
            height: 1px;
            border: none;
        }

        .my-3 {
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .my-4 {
            margin-top: 28px;
            margin-bottom: 15px;
        }

        .product-table {
            margin-bottom: 28px;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .bg-white {
            background-color: #fff;
        }

        .text-right {
            text-align: right;
        }

        .delivery-details {
            background-color: white;
            width: 100%;
        }

        .delivery-left {
            float: left;
            width: 60%;
        }

        .date-right {
            float: right;
            width: 40%;
        }

        .clear {
            clear: both;
        }

        .delivery-left h5,
        .delivery-left p,
        .date-right h5,
        .date-right p {
            background-color: #fff;
        }

        .track-order,
        .info-area,
        .copyright-text {
            text-align: center;
        }

        .track-order p {
            font-size: 14px;
        }

        .track-order-btn {
            padding: 14px 22px;
            margin-top: 16px;
            border-radius: 5px;
            color: #fff !important;
            background-color: #3a953c;
            border-color: #fff;
            border: none;
            text-decoration: none;
        }

        .info-area {
            margin-top: 2rem;
        }

        .copyright-text {
            text-align: center;
            background-color: #fff;
            margin-top: 4rem;
            margin-bottom: 4rem;
        }

        .mt-2 {
            margin-top: 12px;
        }

        .mb-3 {
            margin-bottom: 30px;
        }

        .footer-area {
            margin-top: 2rem;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 768px) {
            .container {
                max-width: 100%;
                padding: 1rem;
                margin: auto;
            }

            .product-name,
            .product-price {
                font-size: 10px;
            }
        }

        @media (max-width: 900px) {
            .container {
                max-width: 100%;
                padding: 1rem;
                margin: auto;
            }

            .product-name,
            .product-price {
                font-size: 12px;
            }

            table {
                border: none;
            }

            .card-text-area {
                padding: 1rem;
            }
        }

    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            @include('layouts.mail.header')

            @yield('content')

            @include('layouts.mail.footer')
        </div>
    </div>
</body>

</html>

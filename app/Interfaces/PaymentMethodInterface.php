<?php

namespace App\Interfaces;

interface PaymentMethodInterface {
    const ONLINE_PAYMENT = 'Online Payment';
    const POS = 'POS';
    const CASH = 'Cash';
}

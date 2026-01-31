<?php

namespace App\Helpers\Plugins;

use App\Models\Order;
use App\Models\UserShippingInformation;

class OrderHelper
{
    const PAYMENT_COD = "cod";
    const PAYMENT_STRIPE = "stripe";
    const PAYMENT_PAYPAL = "paypal";

    const ACTIVE_ORDERS = [
        Order::STATUS_CREATED,
        Order::STATUS_PENDING,
        Order::STATUS_PROCESSING,
        Order::STATUS_SHIPPED,
    ];

}

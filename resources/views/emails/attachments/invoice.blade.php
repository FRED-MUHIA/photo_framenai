<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 24px; color: #111827; font-family: Arial, Helvetica, sans-serif;">
@php
    $currency = $order->currency ?: config('shop.currency', 'KES');
    $paymentDetails = \App\Support\PaymentDetails::forOrder($order);
@endphp

<h1 style="margin: 0 0 6px; font-size: 28px;">Invoice</h1>
<p style="margin: 0 0 24px; color: #64748b;">Order: {{ $order->order_number }}</p>

<table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 24px; border-collapse: collapse;">
    <tr>
        <td style="width: 50%; vertical-align: top;">
            <p style="margin: 0 0 6px; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">Billed To</p>
            <p style="margin: 0; font-weight: 700;">{{ $order->customer_name }}</p>
            <p style="margin: 4px 0 0; color: #475569;">{{ $order->customer_email }}</p>
            @if($order->customer_phone)
                <p style="margin: 4px 0 0; color: #475569;">{{ $order->customer_phone }}</p>
            @endif
        </td>
        <td style="width: 50%; vertical-align: top;">
            <p style="margin: 0 0 6px; color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase;">Shipping Address</p>
            <p style="margin: 0; color: #475569;">{{ $order->shipping_address_line1 }}</p>
            <p style="margin: 4px 0 0; color: #475569;">{{ $order->shipping_country }}</p>
        </td>
    </tr>
</table>

<div style="margin-bottom: 24px; border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc; padding: 14px;">
    <h2 style="margin: 0 0 12px; font-size: 16px;">Payment Details</h2>
    <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; font-size: 13px;">
        <tr>
            <td style="width: 50%; padding: 4px 0; color: #64748b; font-weight: 700;">Payment Method</td>
            <td style="width: 50%; padding: 4px 0; color: #64748b; font-weight: 700;">Payment Status</td>
        </tr>
        <tr>
            <td style="padding: 0 0 10px; font-weight: 700;">{{ $paymentDetails['method'] }}</td>
            <td style="padding: 0 0 10px; font-weight: 700;">{{ $paymentDetails['status'] }}</td>
        </tr>
    </table>
    @foreach($paymentDetails['instructions'] as $instruction)
        <p style="margin: 4px 0 0; color: #475569; font-size: 13px;">{{ $instruction }}</p>
    @endforeach
</div>

<table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; font-size: 13px;">
    <thead>
        <tr>
            <th align="left" style="padding: 10px; border-bottom: 1px solid #cbd5e1; color: #64748b;">Item</th>
            <th align="left" style="padding: 10px; border-bottom: 1px solid #cbd5e1; color: #64748b;">Qty</th>
            <th align="left" style="padding: 10px; border-bottom: 1px solid #cbd5e1; color: #64748b;">Unit Price</th>
            <th align="right" style="padding: 10px; border-bottom: 1px solid #cbd5e1; color: #64748b;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $item->product_name }}</td>
                <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $item->quantity }}</td>
                <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $currency }} {{ number_format($item->unit_price, 2) }}</td>
                <td align="right" style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $currency }} {{ number_format($item->line_total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table cellpadding="0" cellspacing="0" width="280" align="right" style="margin-top: 24px; border-collapse: collapse; font-size: 13px;">
    <tr>
        <td style="padding: 6px 0;">Subtotal</td>
        <td align="right" style="padding: 6px 0;">{{ $currency }} {{ number_format($order->subtotal, 2) }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0;">Shipping</td>
        <td align="right" style="padding: 6px 0;">{{ $currency }} {{ number_format($order->shipping_amount, 2) }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0;">Discount</td>
        <td align="right" style="padding: 6px 0;">-{{ $currency }} {{ number_format($order->discount_amount, 2) }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; border-top: 1px solid #cbd5e1; font-weight: 800;">Total</td>
        <td align="right" style="padding: 8px 0; border-top: 1px solid #cbd5e1; font-weight: 800;">{{ $currency }} {{ number_format($order->total_amount, 2) }}</td>
    </tr>
</table>
</body>
</html>

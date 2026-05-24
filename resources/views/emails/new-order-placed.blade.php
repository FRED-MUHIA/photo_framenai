<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New order placed</title>
</head>
<body style="margin: 0; padding: 0; background: #eef2f6; color: #182230; font-family: Arial, Helvetica, sans-serif;">
@php
    $currency = $order->currency ?: config('shop.currency', 'KES');
    $shippingLabel = data_get(config('shop.shipping_options'), $order->shipping_option . '.label')
        ?: str($order->shipping_option)->replace('_', ' ')->title();
@endphp

<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; background: #eef2f6;">
    <tr>
        <td align="center" style="padding: 14px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 640px; border-collapse: separate; border-spacing: 0; overflow: hidden; border: 1px solid #cbd5e1; border-radius: 10px; background: #ffffff;">
                <tr>
                    <td style="padding: 22px 22px 12px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td style="width: 36px; vertical-align: middle;">
                                    <div style="height: 34px; width: 34px; border-radius: 50%; background: #e51b2b; color: #ffffff; font-size: 11px; font-weight: 800; line-height: 34px; text-align: center;">NAI</div>
                                </td>
                                <td style="padding-left: 10px; vertical-align: middle; font-size: 16px; font-weight: 800; color: #111827;">Nai Prints</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 22px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-radius: 10px; background: linear-gradient(135deg, #2a1114 0%, #78111f 100%);">
                            <tr>
                                <td style="padding: 30px 26px;">
                                    <p style="margin: 0 0 8px; color: #ffffff; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">New order</p>
                                    <h1 style="margin: 0; color: #ffffff; font-size: 32px; line-height: 1.05; font-weight: 900;">A customer placed an order</h1>
                                    <p style="margin: 16px 0 0; max-width: 430px; color: #ffffff; font-size: 13px; line-height: 1.55; font-weight: 700;">Review the order details and status below.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px 22px 0;">
                        <p style="margin: 0 0 12px; color: #475467; font-size: 13px; line-height: 1.6;">Order <strong style="color: #111827;">{{ $order->order_number }}</strong> was placed by {{ $order->customer_name }}.</p>
                        <a href="{{ $adminOrderUrl }}" style="display: inline-block; border-radius: 999px; background: #e51b2b; color: #ffffff; font-size: 12px; font-weight: 800; line-height: 1; padding: 12px 18px; text-decoration: none;">Open Order</a>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Customer</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Email</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $order->customer_email }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Order Status</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ str($order->status)->replace('_', ' ')->title() }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Payment Status</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ str($order->payment_status)->replace('_', ' ')->title() }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Shipping</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $shippingLabel }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; color: #64748b; font-size: 12px; font-weight: 700;">Total Amount</td>
                                <td align="right" style="padding: 13px 14px; color: #111827; font-size: 12px; font-weight: 900;">{{ $currency }} {{ number_format((float) $order->total_amount, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 22px 24px;">
                        <p style="margin: 0; color: #94a3b8; font-size: 10px; line-height: 1.5;">This internal alert was generated by Nai Prints.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

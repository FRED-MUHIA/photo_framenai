<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your order has been updated</title>
</head>
<body style="margin: 0; padding: 0; background: #eef2f6; color: #182230; font-family: Arial, Helvetica, sans-serif;">
@php
    $currency = $order->currency ?: config('shop.currency', 'KES');
    $shippingLabel = data_get(config('shop.shipping_options'), $order->shipping_option . '.label')
        ?: str($order->shipping_option)->replace('_', ' ')->title();
    $statusLabel = str($order->status)->replace('_', ' ')->title();
    $paymentLabel = str($order->payment_status)->replace('_', ' ')->title();
    $headline = match ($order->status) {
        'processing' => 'Your order is being processed',
        'printing' => 'Your order is being printed',
        'shipped' => 'Your order has been shipped',
        'completed' => 'Your order has been completed',
        'cancelled' => 'Your order has been cancelled',
        default => 'Your order is pending review',
    };
    $summary = match ($order->status) {
        'processing' => 'We have started preparing your order for production.',
        'printing' => 'Your order is now in print production.',
        'shipped' => 'Your order has left production and is on the way.',
        'completed' => 'Your order has been completed. Thank you for printing with us.',
        'cancelled' => 'Your order has been cancelled. Contact us if you need help placing a new order.',
        default => 'We have received your order and will review it shortly.',
    };
@endphp

<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; background: #eef2f6; padding: 0;">
    <tr>
        <td align="center" style="padding: 14px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="width: 100%; max-width: 640px; border-collapse: separate; border-spacing: 0; overflow: hidden; border: 1px solid #cbd5e1; border-radius: 10px; background: #ffffff;">
                <tr>
                    <td style="padding: 22px 22px 12px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td style="width: 36px; vertical-align: middle;">
                                    <div style="height: 34px; width: 34px; border-radius: 50%; background: #e51b2b; color: #ffffff; font-size: 11px; font-weight: 800; line-height: 34px; text-align: center;">PF</div>
                                </td>
                                <td style="padding-left: 10px; vertical-align: middle; font-size: 16px; font-weight: 800; color: #111827;">Photo Frames</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 22px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border-radius: 10px; background: linear-gradient(135deg, #2a1114 0%, #78111f 100%);">
                            <tr>
                                <td style="padding: 30px 26px;">
                                    <p style="margin: 0 0 8px; color: #ffffff; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">Order update</p>
                                    <h1 style="margin: 0; color: #ffffff; font-size: 32px; line-height: 1.05; font-weight: 900;">{{ $headline }}</h1>
                                    <p style="margin: 16px 0 0; max-width: 430px; color: #ffffff; font-size: 13px; line-height: 1.55; font-weight: 700;">{{ $summary }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px 22px 0;">
                        <p style="margin: 0 0 12px; color: #475467; font-size: 13px; line-height: 1.6;">Hello {{ $order->customer_name }},</p>
                        <p style="margin: 0 0 20px; color: #475467; font-size: 13px; line-height: 1.6;">Order <strong style="color: #111827;">{{ $order->order_number }}</strong> status is now <strong style="color: #111827;">{{ $statusLabel }}</strong>. The latest details are below.</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Order Number</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Order Status</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $statusLabel }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Payment Status</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $paymentLabel }}</td>
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

                @if(! empty($changes))
                    <tr>
                        <td style="padding: 0 22px 22px;">
                            <p style="margin: 0 0 10px; color: #111827; font-size: 14px; font-weight: 900;">What changed</p>
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                                @foreach($changes as $change)
                                    <tr>
                                        <td style="padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">{{ $change['label'] }}</td>
                                        <td align="right" style="padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $change['from'] }} to {{ $change['to'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding: 0 22px 24px;">
                        <h2 style="margin: 0 0 8px; color: #111827; font-size: 17px; line-height: 1.3; font-weight: 900;">Need help with this update?</h2>
                        <p style="margin: 0 0 10px; color: #475467; font-size: 12px; line-height: 1.6;">
                            Email:
                            <a href="mailto:info@photoframes.co.ke" style="color: #e51b2b; text-decoration: none;">info@photoframes.co.ke</a>
                            &nbsp; Phone: 254745506619 &nbsp;
                            <a href="{{ url('/') }}" style="color: #e51b2b; text-decoration: none;">Visit our website</a>
                        </p>
                        <p style="margin: 0; color: #94a3b8; font-size: 10px; line-height: 1.5;">This update was generated by Photo Frames. If you have questions, reply to this email.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

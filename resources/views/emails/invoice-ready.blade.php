<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your invoice is ready</title>
</head>
<body style="margin: 0; padding: 0; background: #eef2f6; color: #182230; font-family: Arial, Helvetica, sans-serif;">
@php
    $issueDate = optional($order->placed_at)->format('M d, Y') ?: now()->format('M d, Y');
    $dueDate = optional($order->delivery_estimate)->format('M d, Y') ?: now()->addDays(4)->format('M d, Y');
    $currency = $order->currency ?: config('shop.currency', 'KES');
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
                                    <p style="margin: 0 0 8px; color: #ffffff; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">Invoice ready</p>
                                    <h1 style="margin: 0; color: #ffffff; font-size: 32px; line-height: 1.05; font-weight: 900;">Your invoice is ready</h1>
                                    <p style="margin: 16px 0 0; max-width: 430px; color: #ffffff; font-size: 13px; line-height: 1.55; font-weight: 700;">Review the invoice details and complete payment before the due date.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px 22px 0;">
                        <p style="margin: 0 0 12px; color: #475467; font-size: 13px; line-height: 1.6;">Hello {{ $order->customer_name }},</p>
                        <p style="margin: 0 0 20px; color: #475467; font-size: 13px; line-height: 1.6;">Invoice <strong style="color: #111827;">{{ $order->order_number }}</strong> is now available. Use the button below to view or download it.</p>

                        <a href="{{ $invoiceUrl }}" style="display: inline-block; border-radius: 999px; background: #e51b2b; color: #ffffff; font-size: 12px; font-weight: 800; line-height: 1; padding: 12px 18px; text-decoration: none;">View Invoice</a>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 22px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden;">
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Invoice Number</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Issue Date</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $issueDate }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 12px; font-weight: 700;">Due Date</td>
                                <td align="right" style="padding: 13px 14px; border-bottom: 1px solid #e2e8f0; color: #111827; font-size: 12px; font-weight: 900;">{{ $dueDate }}</td>
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
                        <h2 style="margin: 0 0 8px; color: #111827; font-size: 17px; line-height: 1.3; font-weight: 900;">Need help with this update?</h2>
                        <p style="margin: 0 0 10px; color: #475467; font-size: 12px; line-height: 1.6;">
                            Email:
                            <a href="mailto:info@naiprinters.co.ke" style="color: #e51b2b; text-decoration: none;">info@naiprinters.co.ke</a>
                            &nbsp; Phone: 254745506619 &nbsp;
                            <a href="{{ url('/') }}" style="color: #e51b2b; text-decoration: none;">Visit our website</a>
                        </p>
                        <p style="margin: 0; color: #94a3b8; font-size: 10px; line-height: 1.5;">This invoice was generated by Nai Prints. If you have questions, reply to this email.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

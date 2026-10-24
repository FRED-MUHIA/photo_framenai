<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>We received your request</title>
</head>
<body style="margin: 0; padding: 0; background: #f3f4f6; color: #1f2937; font-family: Arial, sans-serif; line-height: 1.5;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; background: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 24px 12px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 620px; border-collapse: collapse; border: 1px solid #d1d5db; background: #ffffff;">
                    <tr>
                        <td style="padding: 24px;">
                            <p style="margin: 0 0 8px; color: #b91c1c; font-size: 12px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">Request received</p>
                            <h1 style="margin: 0; color: #111827; font-size: 24px; line-height: 1.2;">Thanks{{ $requesterName ? ', ' . $requesterName : '' }}</h1>
                            <p style="margin: 14px 0 0; color: #4b5563; font-size: 14px;">We received your request for <strong>{{ $pageTitle }}</strong>. Our team will review it and contact you shortly.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 24px 24px;">
                            <h2 style="margin: 0 0 10px; color: #111827; font-size: 16px;">Submitted details</h2>
                            <table cellpadding="8" cellspacing="0" border="1" width="100%" style="border-collapse: collapse; border-color: #e5e7eb; font-size: 13px;">
                                <tbody>
                                    @foreach($fields as $label => $value)
                                        <tr>
                                            <td style="width: 36%; color: #6b7280;">{{ $label }}</td>
                                            <td style="color: #111827;">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p style="margin: 18px 0 0; color: #4b5563; font-size: 13px;">Need to add anything? Reply to this email or contact <a href="mailto:info@photoframes.co.ke" style="color: #b91c1c;">info@photoframes.co.ke</a>.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Business Stationery Request</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <h2 style="margin: 0 0 12px;">New Request: {{ $pageTitle }}</h2>

    <p style="margin: 0 0 6px;"><strong>Page Title:</strong> {{ $pageTitle }}</p>
    <p style="margin: 0 0 6px;"><strong>Page Slug:</strong> {{ $pageSlug }}</p>
    @if($requestUrl)
        <p style="margin: 0 0 6px;"><strong>Request URL:</strong> {{ $requestUrl }}</p>
    @endif

    <p style="margin: 0 0 6px;"><strong>Requester Name:</strong> {{ $requesterName ?: 'Not provided' }}</p>
    <p style="margin: 0 0 6px;"><strong>Requester Email:</strong> {{ $requesterEmail ?: 'Not provided' }}</p>
    <p style="margin: 0 0 6px;"><strong>Requester Phone:</strong> {{ $requesterPhone ?: 'Not provided' }}</p>
    <p style="margin: 0 0 12px;"><strong>User ID:</strong> {{ $userId ?: 'Guest' }}</p>

    <h3 style="margin: 18px 0 8px;">Submitted Details</h3>
    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; border-color: #d1d5db;">
        <thead>
            <tr style="background: #f3f4f6;">
                <th align="left">Field</th>
                <th align="left">Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fields as $label => $value)
                <tr>
                    <td style="width: 35%;">{{ $label }}</td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


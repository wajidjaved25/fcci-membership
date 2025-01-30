<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; font-size: 20px; font-weight: bold; }
        .container { padding: 20px; }
        .details { margin-top: 20px; }
        .details p { margin: 5px 0; }
        .footer { margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        FCCI Membership Receipt
    </div>

    <div class="container">
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Membership Class:</strong> {{ $registration->membership_class }}</p>
        <p><strong>Fee Paid:</strong> Rs. {{ $registration->fee_paid }}</p>
        <p><strong>Date Paid:</strong> {{ $registration->fee_paid_at }}</p>
    </div>

    <div class="footer">
        <p>Thank you for your payment.</p>
    </div>
</body>
</html>

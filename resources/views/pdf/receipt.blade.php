<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }
        .header { 
            text-align: center; 
            font-size: 24px; 
            font-weight: bold; 
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .header img { 
            height: 100px; 
            margin-bottom: 10px; 
        }
        .content {
            margin-top: 20px; 
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .content strong {
            color: #2980b9;
        }
        .footer {
            margin-top: 30px; 
            text-align: center; 
            font-size: 14px; 
            color: #7f8c8d;
        }
        .footer p {
            margin: 0;
        }
        .highlight {
            background-color: #f1c40f;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Implementing the company logo -->
        <img src="{{ public_path('images/fcci-logo.png') }}" alt="Company Logo" class="company-logo">
        <p>FCCI Membership Fee Receipt</p>
    </div>
    <div class="content">
        <p><strong>Company Name:</strong> {{ $registration->company_name }}</p>
        <p><strong>Membership Class:</strong> {{ $registration->membership_class }}</p>
        <p><strong>Fee Amount:</strong> Rs. {{ $registration->fee_amount }}</p>
        <p><strong>Date of Payment:</strong> {{ $registration->fee_paid_at }}</p>
        <p><strong>Receipt ID:</strong> FCCI-{{ $registration->id }}</p>
        
        <!-- Highlighting the Fee Amount -->
        <div class="highlight">
            <p>Total Fee Paid: Rs. {{ $registration->fee_paid }}</p>
        </div>
    </div>
    <div class="footer">
        <p>Thank you for your payment!</p>
    </div>
</body>
</html>

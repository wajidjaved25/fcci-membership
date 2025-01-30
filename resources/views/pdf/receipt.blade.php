<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable Receipt</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .receipt {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            padding: 30mm; /* Set padding for top, bottom, left, and right */
            background-color: #fff;
            margin: 0;
            border: 1px solid #f1f1f1;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .logo {
            width: 120px;
            margin-bottom: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-top: 0;
            font-weight: 600;
        }
        .content {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-top: 20px;
        }
        .highlight {
            color: #007bff;
            font-weight: 600;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .footer img {
            width: 90px;
            margin-bottom: 10px;
        }
        .btn {
            display: block;
            margin: 30px auto;
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="receipt" id="receipt">
        <div style="text-align: center;">
            <img src="https://upload.wikimedia.org/wikipedia/commons/1/1a/Logo-FCCI.jpg" class="logo" alt="FCCI Logo">
            <h2>FCCI Membership Fee Receipt</h2>
        </div>

        <div class="content">
            <p><strong>Company Name:</strong> <span class="highlight">{{ $registration->company_name }}</span></p>
            <p><strong>Membership Class:</strong> <span class="highlight">{{ $registration->membership_class }}</span></p>
            <p><strong>Fee Amount:</strong> <span class="highlight">Rs. {{ $registration->fee_paid }}</span></p>
            <p><strong>Date of Payment:</strong> <span class="highlight">{{ $registration->fee_paid_at }}</span></p>
            <p><strong>Receipt ID:</strong> <span class="highlight">FCCI-{{ $registration->id }}</span></p>
        </div>

        <div class="footer">
            <img src="https://via.placeholder.com/90" class="logo" alt="SMS App Logo">
            <p>Powered by SMS App</p>
        </div>
    </div>

    <button class="btn" onclick="window.print()">Print Receipt</button>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Registration</title>
    <style>
        /* Base Styling */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333;
        }

        /* Header Styling */
        header {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, #ffffff, #dbe6f1);
            color: #1d3557;
            border-bottom: 4px solid #e1ad01;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        header img {
            max-width: 90px;
        }

        header h1 {
            font-size: 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Main Container */
        .container {
            max-width: 900px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        /* Section Titles */
        h2 {
            font-size: 1.5rem;
            color: #1d3557;
            margin-bottom: 15px;
            border-bottom: 3px solid #e1ad01;
            padding-bottom: 6px;
            font-weight: 600;
        }

        /* Info Text */
        .info-text {
            font-size: 1rem;
            color: #555;
            background: #f8f9fc;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        /* Download PDF Button */
        .btn-download {
            background: #1d3557;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
            display: block;
            margin-bottom: 20px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-download:hover {
            background: #16324f;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 1rem;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #dbe6f1;
            color: #1d3557;
            font-weight: bold;
        }

        td {
            background: #ffffff;
        }

        tbody tr:hover td {
            background-color: #eef3fa;
            transition: background 0.3s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            header h1 {
                font-size: 1.8rem;
            }

            h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/fcci-logo.png') }}" alt="FCCI Logo">
        <h1>Membership Registration</h1>
    </header>

    <div class="container">
        <h2>Company Details</h2>
        <table>
            <tr><th>Company Name</th> <td>{{ $registration->company_name }}</td></tr>
            <tr><th>Address</th> <td>{{ $registration->address }}</td></tr>
            <tr><th>Mobile</th> <td>{{ $registration->mobile }}</td></tr>
            <tr><th>Email</th> <td>{{ $registration->email }}</td></tr>
            <tr><th>Website</th> <td>{{ $registration->website }}</td></tr>
            <tr><th>Main Business</th> <td>{{ $registration->main_business }}</td></tr>
        </table>

        <h2>Additional Information</h2>
        <div class="info-text">
            NTN: <strong>{{ $registration->ntn }}</strong> <br>
            Sales Tax Number: <strong>{{ $registration->sales_tax_number }}</strong> <br>
            Product Line: <strong>{{ $registration->product_line }}</strong>
        </div>


        <h2>Directors/Partners</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>CNIC</th>
                    <th>Relation</th>
                    <th>Gender</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registration->directorsPartners as $director)
                    <tr>
                        <td>{{ $director->name }}</td>
                        <td>{{ $director->cnic_number }}</td>
                        <td>{{ $director->relation }}</td>
                        <td>{{ $director->gender }}</td>
                        <td>{{ $director->home_address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Uploaded Documents</h2>
        <table>
            <thead><tr><th>Document Type</th></tr></thead>
            <tbody>
                @foreach ($registration->documents as $document)
                    <tr><td>{{ $document->document_type }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
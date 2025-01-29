<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Registration</title>
    <style>
        /* Base Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333;
        }

        /* Header Styling */
        header {
            text-align: center;
            padding: 30px 0;
            background-color: #004080;
            color: white;
            margin-bottom: 40px;
            border-bottom: 5px solid #003366;
        }

        header img {
            max-width: 120px;
        }

        header h1 {
            font-size: 2.5rem;
            margin: 10px 0 0;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Details Section */
        .details {
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
        }

        /* Section Headings */
        .details h2 {
            font-size: 1.8rem;
            color: #004080;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 3px solid #004080;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            font-size: 1rem;
        }

        th {
            background-color: #004080;
            color: white;
            font-weight: bold;
        }

        td {
            background-color: #f9f9f9;
        }

        /* Table Row Striping and Hover */
        tbody tr:nth-child(odd) td {
            background-color: #ffffff;
        }

        tbody tr:hover td {
            background-color: #eaf3ff;
        }

        /* No Border Table */
        th, td {
            border: none;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .details {
                padding: 20px;
            }

            header h1 {
                font-size: 2rem;
            }

            .details h2 {
                font-size: 1.5rem;
            }

            table th, table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/fcci-logo.png') }}" alt="FCCI Logo">
        <h1>Membership Registration Application</h1>
    </header>
    <div class="details">
        <h2>Company Details</h2>
        <table>
            <tr>
                <th>Company Name</th>
                <td>{{ $registration->company_name }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{ $registration->address }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ $registration->mobile }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $registration->email }}</td>
            </tr>
            <tr>
                <th>Website</th>
                <td>{{ $registration->website }}</td>
            </tr>
            <tr>
                <th>Main Business</th>
                <td>{{ $registration->main_business }}</td>
            </tr>
        </table>

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
            <thead>
                <tr>
                    <th>Document Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registration->documents as $document)
                    <tr>
                        <td>{{ $document->document_type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

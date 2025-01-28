<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .logo {
            max-width: 150px;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/fcci-logo.png') }}" alt="FCCI Logo" class="logo">
        <h1>Membership Registration</h1>
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
            @foreach ($registration->documents as $document)
                <tr>
                    <td>{{ $document->document_type }}</td>
                    <td><a href="{{ public_path('storage/' . $document->document_path) }}" target="_blank">Download</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>

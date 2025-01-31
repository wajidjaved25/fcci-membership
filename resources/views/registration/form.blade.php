<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for {{ $formDetails->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        /* General body styling */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Form container styling */
        .form-container {
            max-width: 800px;
            margin: auto;
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        /* Hover effect for form container */
        .form-container:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        /* Header logo styling */
        .header-logo img {
            width: 150px;
            margin: 0 auto;
            display: block;
        }
        /* Primary button styling */
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }
        /* Button hover effect */
        .btn-primary:hover {
            background-color: #45a049;
        }
        /* Input field styling */
        .input-field {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 12px;
            width: 100%;
            transition: border-color 0.3s ease-in-out;
        }
        /* Focus effect for input fields */
        .input-field:focus {
            border-color: #4CAF50;
        }
        /* Section title styling */
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        /* Label styling */
        .label {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }
        /* Help text styling */
        .help-text {
            font-size: 12px;
            color: #888;
        }
        /* Remove director button styling */
        .remove-director {
            background-color: #ff6363;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease-in-out;
        }
        /* Hover effect for remove button */
        .remove-director:hover {
            background-color: #ff4c4c;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Header Section -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 py-4 shadow-md">
        <div class="header-logo text-center">
            <img src="{{ asset('images/fcci-logo.png') }}" alt="FCCI Logo">
        </div>
    </header>
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
        <strong>Success!</strong> {{ session('success') }}
    </div>

    @if(session('download_url'))
        <script>
            window.onload = function() {
                var downloadUrl = "{{ session('download_url') }}";
                if (downloadUrl) {
                    window.location.href = downloadUrl;
                }
            };
        </script>
    @endif
@endif

    <!-- Main Form Section -->
    <main class="container mx-auto px-4 py-10">
        <div class="form-container">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Register for {{ $formDetails->name }}</h1>
            <p class="text-center text-gray-600 mb-8">{{ $formDetails->description }}</p>

            <!-- Error Messages Display -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg">
                    <strong>Whoops! There were some problems with your input.</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="registration-form" action="{{ route('register.submit', $formDetails->name) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Company Details Section -->
                <h2 class="section-title">Company Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    @foreach([
                        'company_name' => 'Company Name', 
                        'address' => 'Address', 
                        'telephone' => 'Telephone', 
                        'mobile' => 'Mobile', 
                        'email' => 'Email', 
                        'website' => 'Website'
                    ] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="label">{{ $label }}</label>
                            <!-- Input field with inline help text -->
                            <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" class="input-field">
                            <span class="help-text">Please enter your {{ strtolower($label) }}.</span>
                        </div>
                    @endforeach
                </div>

                <!-- Membership Details Section -->
                <h2 class="section-title">Membership & Business Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="membership_class" class="label">Membership Class</label>
                        <!-- Dropdown with inline help text -->
                        <select id="membership_class" name="membership_class" class="input-field" required>
                            <option value="Corporate" {{ old('membership_class') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                            <option value="Associate" {{ old('membership_class') == 'Associate' ? 'selected' : '' }}>Associate</option>
                        </select>
                        <span class="help-text">Select your membership class.</span>
                    </div>
                    <div>
                        <label for="year_establishment" class="label">Year of Establishment</label>
                        <!-- Input field with inline help text -->
                        <input type="number" id="year_establishment" name="year_establishment" value="{{ old('year_establishment') }}" class="input-field" required>
                        <span class="help-text">Enter the year your company was established.</span>
                    </div>
                    <div>
                        <label for="ntn" class="label">NTN</label>
                        <!-- Input field with inline help text -->
                        <input type="text" id="ntn" name="ntn" value="{{ old('ntn') }}" class="input-field">
                        <span class="help-text">Enter your NTN (National Tax Number).</span>
                    </div>
                    <div>
                        <label for="sales_tax_number" class="label">Sales Tax Number</label>
                        <!-- Input field with inline help text -->
                        <input type="text" id="sales_tax_number" name="sales_tax_number" value="{{ old('sales_tax_number') }}" class="input-field">
                        <span class="help-text">Enter your Sales Tax Number.</span>
                    </div>
                    <div>
                        <label for="main_business" class="label">Main Business</label>
                        <!-- Input field with inline help text -->
                        <input type="text" id="main_business" name="main_business" value="{{ old('main_business') }}" class="input-field">
                        <span class="help-text">Describe your main business activity.</span>
                    </div>
                    <div>
                        <label for="product_line" class="label">Product Line</label>
                        <!-- Input field with inline help text -->
                        <input type="text" id="product_line" name="product_line" value="{{ old('product_line') }}" class="input-field">
                        <span class="help-text">List the main products your company sells.</span>
                    </div>
                </div>

                <!-- Testimonial Section -->
                <h2 class="section-title">Testimonial Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    @foreach(['testimonial_1' => 'Testimonial 1', 'testimonial_2' => 'Testimonial 2'] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="label">{{ $label }}</label>
                            <!-- Input field with inline help text -->
                            <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" class="input-field">
                            <span class="help-text">Provide a short testimonial from your client.</span>
                        </div>
                    @endforeach
                </div>

<!-- Director/Partner Section -->
<h2 class="section-title">Proprietor/Directors/Partners Details</h2>
<div id="directors-container">
    <div class="director-row bg-gray-100 p-6 rounded-lg mb-6 shadow-sm">
        <h3 class="text-xl font-bold text-gray-700 mb-4">Proprietor/Director/Partner 1</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach(['name' => 'Name', 'relation' => 'Son of/ Daughter of/ Wife of', 'date_of_birth' => 'Date of Birth', 'gender' => 'Gender', 'home_address' => 'Home Address', 'phone' => 'Phone'] as $field => $label)
                <div>
                    <label class="label">{{ $label }}</label>
                    <input type="{{ $field === 'date_of_birth' ? 'date' : 'text' }}" name="directors[0][{{ $field }}]" class="input-field" required>
                    <span class="help-text">Enter the {{ strtolower($label) }} of the proprietor or director.</span>
                </div>
            @endforeach
<div>
                <label for="directors[0][cnic_number]" class="label">CNIC Number</label>
                <input type="text" name="directors[0][cnic_number]" class="input-field" required>
            </div>
            <div>
                <label for="directors[0][cnic_issue_date]" class="label">CNIC Issue Date</label>
                <input type="date" name="directors[0][cnic_issue_date]" class="input-field" required>
            </div>
            <div>
                <label for="directors[0][cnic_expiry_date]" class="label">CNIC Expiry Date</label>
                <input type="date" name="directors[0][cnic_expiry_date]" class="input-field" required>
            </div>
            <div>
                <label class="label">CNIC Front</label>
                <input type="file" name="directors[0][cnic_front]" class="input-field" accept="image/*,.pdf" required>
            </div>
            <div>
                <label class="label">CNIC Back</label>
                <input type="file" name="directors[0][cnic_back]" class="input-field" accept="image/*,.pdf" required>
            </div>
        </div>
        <button type="button" class="remove-director bg-red-500 text-white px-4 py-2 rounded-lg mt-4">Remove</button>
    </div>
</div>
<button type="button" id="add-director" class="btn-primary">Add Another Director/Partner</button>

<!-- JavaScript to Handle Adding/Removing Directors -->
<script>
    document.getElementById('add-director').addEventListener('click', function () {
        const container = document.getElementById('directors-container');
        const newIndex = container.children.length;
        const newDirector = document.querySelector('.director-row').cloneNode(true);

        newDirector.querySelectorAll('input').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`);
            input.value = '';
        });

        newDirector.querySelector('h3').textContent = `Proprietor/Director/Partner ${newIndex + 1}`;
        container.appendChild(newDirector);

        newDirector.querySelector('.remove-director').addEventListener('click', function () {
            newDirector.remove();
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-director')) {
            e.target.closest('.director-row').remove();
        }
    });
</script>
                <!-- Document Upload Section -->
                <h2 class="section-title mt-8">Required Documents</h2>
               @foreach ($documentRequirements as $index => $document)
    <div class="mb-4">
        <label for="documents[{{ $index }}]" class="block text-sm font-medium text-gray-700">
            {{ $document->document_name }} 
            @if ($document->is_required) <span class="text-red-500">*</span> @endif
        </label>
        <input type="file" name="documents[{{ $index }}]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" 
            @if ($document->is_required) required @endif>
        <input type="hidden" name="document_names[{{ $index }}]" value="{{ $document->document_name }}">
    </div>
@endforeach
                <!-- Submit Button -->
                <div class="text-center mt-8">
                    <button type="submit" class="btn-primary">Submit Registration</button>
                </div>
            </form>
        </div>
    </main>

    <!-- JavaScript for Adding/Removing Directors -->
    <script>
        document.getElementById('add-director').addEventListener('click', function () {
    const container = document.getElementById('directors-container');
    const newDirector = document.querySelector('.director-row').cloneNode(true);
    const index = container.querySelectorAll('.director-row').length; 
    
    newDirector.querySelectorAll('input, select').forEach(input => {
        const name = input.getAttribute('name').replace(/\[\d+\]/, `[${index}]`);
        input.setAttribute('name', name);
        input.value = ''; // Clear input value
    });

    container.appendChild(newDirector);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-director')) {
        e.target.closest('.director-row').remove();
    }
        });
    </script>
@if(session('download_url'))
    <script>
        window.onload = function() {
            var downloadUrl = "{{ session('download_url') }}";
            window.location.href = downloadUrl;
        };
    </script>
@endif
</body>
</html>

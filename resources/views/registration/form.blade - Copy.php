<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for {{ $formDetails->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-500 to-purple-600 shadow">
        <div class="container mx-auto px-4 py-6 flex justify-center">
            <img src="{{ asset('images/fcci-logo.png') }}" alt="FCCI Logo" class="h-16">
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Register for {{ $formDetails->name }}</h1>
            <p class="text-center text-gray-600 mb-8">{{ $formDetails->description }}</p>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
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

                <!-- Company Details -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Company Details</h2>
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
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                            <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                        </div>
                    @endforeach
                </div>

                <!-- Membership & Business Details -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Membership & Business Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="membership_class" class="block text-sm font-medium text-gray-700">Membership Class</label>
                        <select id="membership_class" name="membership_class" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            <option value="Corporate" {{ old('membership_class') == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                            <option value="Associate" {{ old('membership_class') == 'Associate' ? 'selected' : '' }}>Associate</option>
                        </select>
                    </div>
                    <div>
                        <label for="year_establishment" class="block text-sm font-medium text-gray-700">Year of Establishment</label>
                        <input type="number" id="year_establishment" name="year_establishment" value="{{ old('year_establishment') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="ntn" class="block text-sm font-medium text-gray-700">NTN</label>
                        <input type="text" id="ntn" name="ntn" value="{{ old('ntn') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="sales_tax_number" class="block text-sm font-medium text-gray-700">Sales Tax Number</label>
                        <input type="text" id="sales_tax_number" name="sales_tax_number" value="{{ old('sales_tax_number') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="main_business" class="block text-sm font-medium text-gray-700">Main Business</label>
                        <input type="text" id="main_business" name="main_business" value="{{ old('main_business') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="product_line" class="block text-sm font-medium text-gray-700">Product Line</label>
                        <input type="text" id="product_line" name="product_line" value="{{ old('product_line') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                </div>

                <!-- Testimonial Details -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Testimonial Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    @foreach(['testimonial_1' => 'Testimonial 1', 'testimonial_2' => 'Testimonial 2'] as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                            <input type="text" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                        </div>
                    @endforeach
                </div>

                <!-- Proprietor/Directors/Partners Details -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Proprietor/Directors/Partners Details</h2>
                <div id="directors-container">
                    <div class="director-row bg-gray-100 p-4 rounded-lg mb-4">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Proprietor/Director/Partner</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach(['name' => 'Name', 'cnic_number' => 'CNIC Number', 'relation' => 'Son of / Daughter of / Wife of', 'date_of_birth' => 'Date of Birth', 'gender' => 'Gender', 'home_address' => 'Home Address', 'phone' => 'Phone'] as $field => $label)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                    <input type="text" name="directors[0][{{ $field }}]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="remove-director bg-red-500 text-white px-4 py-2 rounded-lg mt-4">Remove</button>
                    </div>
                </div>
                <button type="button" id="add-director" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Add Another Director/Partner</button>

                <!-- Required Documents -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4">Required Documents</h2>
                @foreach ($documentRequirements as $index => $document)
                <div class="mb-4">
                    <label for="documents[{{ $index }}]" class="block text-sm font-medium text-gray-700">{{ $document->document_name }}</label>
                    <input type="file" name="documents[{{ $index }}]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                </div>
                @endforeach

                <!-- Submit -->
                <div class="text-center mt-6">
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg shadow hover:bg-green-600">
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('add-director').addEventListener('click', function () {
            const container = document.getElementById('directors-container');
            const newDirector = document.querySelector('.director-row').cloneNode(true);
            newDirector.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newDirector);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-director')) {
                e.target.closest('.director-row').remove();
            }
        });
    </script>
</body>
</html>

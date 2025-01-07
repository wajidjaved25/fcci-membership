<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for {{ $formDetails->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.5.0/cdn.min.js"></script>
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

            <form action="{{ route('register.submit', $formDetails->name) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Company Details -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Company Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input type="text" id="company_name" name="company_name" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address" name="address" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-gray-700">Telephone</label>
                        <input type="text" id="telephone" name="telephone" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile</label>
                        <input type="text" id="mobile" name="mobile" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <input type="url" id="website" name="website" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="testimonial_1" class="block text-sm font-medium text-gray-700">Testimonial 1</label>
                        <input type="text" id="testimonial_1" name="testimonial_1" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="testimonial_2" class="block text-sm font-medium text-gray-700">Testimonial 2</label>
                        <input type="text" id="testimonial_2" name="testimonial_2" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="membership_class" class="block text-sm font-medium text-gray-700">Membership Class</label>
                        <select id="membership_class" name="membership_class" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            <option value="Corporate">Corporate</option>
                            <option value="Associate">Associate</option>
                        </select>
                    </div>
                    <div>
                        <label for="year_establishment" class="block text-sm font-medium text-gray-700">Year of Establishment</label>
                        <input type="number" id="year_establishment" name="year_establishment" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="ntn" class="block text-sm font-medium text-gray-700">NTN</label>
                        <input type="text" id="ntn" name="ntn" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="sales_tax_number" class="block text-sm font-medium text-gray-700">Sales Tax Number</label>
                        <input type="text" id="sales_tax_number" name="sales_tax_number" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="main_business" class="block text-sm font-medium text-gray-700">Main Business</label>
                        <input type="text" id="main_business" name="main_business" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="product_line" class="block text-sm font-medium text-gray-700">Product Line</label>
                        <input type="text" id="product_line" name="product_line" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                    </div>
                </div>

                <!-- Directors/Partners Details -->
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Directors/Partners Details</h2>
                <div id="directors-container">
                    <div class="director-row bg-gray-100 p-4 rounded-lg mb-4">
                        <h3 class="text-lg font-bold text-gray-700 mb-4">Director/Partner 1</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="directors[0][name]" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="directors[0][name]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="directors[0][cnic_number]" class="block text-sm font-medium text-gray-700">CNIC Number</label>
                                <input type="text" name="directors[0][cnic_number]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="directors[0][relation]" class="block text-sm font-medium text-gray-700">Relation</label>
                                <input type="text" name="directors[0][relation]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="directors[0][date_of_birth]" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="directors[0][date_of_birth]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="directors[0][gender]" class="block text-sm font-medium text-gray-700">Gender</label>
                                <select name="directors[0][gender]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label for="directors[0][home_address]" class="block text-sm font-medium text-gray-700">Home Address</label>
                                <input type="text" name="directors[0][home_address]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label for="directors[0][phone]" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" name="directors[0][phone]" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500">
                            </div>
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
                    <input type="hidden" name="document_names[{{ $index }}]" value="{{ $document->document_name }}">
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
        let directorIndex = 1;

        document.getElementById('add-director').addEventListener('click', function () {
            const container = document.getElementById('directors-container');
            const newDirector = document.querySelector('.director-row').cloneNode(true);

            newDirector.querySelectorAll('input, select').forEach((input) => {
                input.name = input.name.replace(/\[\d+\]/, `[${directorIndex}]`);
                input.value = '';
            });

            newDirector.querySelector('h3').textContent = `Director/Partner ${directorIndex + 1}`;

            newDirector.querySelector('.remove-director').addEventListener('click', function () {
                newDirector.remove();
            });

            directorIndex++;
            container.appendChild(newDirector);
        });

        document.querySelectorAll('.remove-director').forEach((btn) => {
            btn.addEventListener('click', function () {
                btn.closest('.director-row').remove();
            });
        });
    </script>
</body>
</html>

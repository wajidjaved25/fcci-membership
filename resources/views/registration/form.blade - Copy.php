@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Register for {{ $formDetails->name }}</h1>
    <p class="text-center">{{ $formDetails->description }}</p>

    <form action="{{ route('register.submit', $formDetails->name) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Company Details -->
        <h2 class="mt-4">Company Details</h2>
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" id="company_name" name="company_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Telephone</label>
            <input type="text" id="telephone" name="telephone" class="form-control">
        </div>

        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input type="text" id="mobile" name="mobile" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="url" id="website" name="website" class="form-control">
        </div>

        <div class="mb-3">
            <label for="testimonial_1" class="form-label">Testimonial 1</label>
            <input type="text" id="testimonial_1" name="testimonial_1" class="form-control">
        </div>

        <div class="mb-3">
            <label for="testimonial_2" class="form-label">Testimonial 2</label>
            <input type="text" id="testimonial_2" name="testimonial_2" class="form-control">
        </div>

        <div class="mb-3">
            <label for="membership_class" class="form-label">Membership Class</label>
            <select id="membership_class" name="membership_class" class="form-control" required>
                <option value="Corporate">Corporate</option>
                <option value="Associate">Associate</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="year_establishment" class="form-label">Year of Establishment</label>
            <input type="number" id="year_establishment" name="year_establishment" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ntn" class="form-label">NTN</label>
            <input type="text" id="ntn" name="ntn" class="form-control">
        </div>

        <div class="mb-3">
            <label for="sales_tax_number" class="form-label">Sales Tax Number</label>
            <input type="text" id="sales_tax_number" name="sales_tax_number" class="form-control">
        </div>

        <div class="mb-3">
            <label for="main_business" class="form-label">Main Business</label>
            <input type="text" id="main_business" name="main_business" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="product_line" class="form-label">Product Line</label>
            <input type="text" id="product_line" name="product_line" class="form-control">
        </div>

        <!-- Directors/Partners Details -->
        <h2 class="mt-4">Directors/Partners Details</h2>
        <div id="directors-container">
            <div class="director-row mb-3">
                <h5>Director/Partner 1</h5>
                <div class="mb-3">
                    <label for="directors[0][name]" class="form-label">Name</label>
                    <input type="text" name="directors[0][name]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="directors[0][relation]" class="form-label">Relation</label>
                    <input type="text" name="directors[0][relation]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="directors[0][gender]" class="form-label">Gender</label>
                    <select name="directors[0][gender]" class="form-control" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="directors[0][date_of_birth]" class="form-label">Date of Birth</label>
                    <input type="date" name="directors[0][date_of_birth]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="directors[0][cnic_number]" class="form-label">CNIC Number</label>
                    <input type="text" name="directors[0][cnic_number]" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="directors[0][home_address]" class="form-label">Home Address</label>
                    <input type="text" name="directors[0][home_address]" class="form-control" required>
                </div>
            </div>
        </div>
        <button type="button" id="add-director" class="btn btn-secondary">Add Another Director/Partner</button>

        <!-- Document Upload -->
        <h2 class="mt-4">Required Documents</h2>
        @foreach ($documentRequirements as $index => $document)
        <div class="mb-3">
            <label for="documents[{{ $index }}]" class="form-label">{{ $document->document_name }}</label>
            <input type="file" name="documents[{{ $index }}]" class="form-control" required>
            <input type="hidden" name="document_names[{{ $index }}]" value="{{ $document->document_name }}">
        </div>
        @endforeach

        <!-- Submit -->
        <button type="submit" class="btn btn-primary mt-4">Submit</button>
    </form>
</div>

<script>
    let directorIndex = 1;

    document.getElementById('add-director').addEventListener('click', function () {
        const container = document.getElementById('directors-container');
        const newDirector = document.querySelector('.director-row').cloneNode(true);

        newDirector.querySelectorAll('input, select').forEach((input) => {
            input.name = input.name.replace(/\[\d+\]/, `[${directorIndex}]`);
            input.value = '';
        });

        directorIndex++;
        container.appendChild(newDirector);
    });
</script>
@endsection

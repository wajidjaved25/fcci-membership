<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\DocumentRequirement;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /**
     * Show the registration form for the specified type.
     */
    public function showForm($form)
    {
        $formDetails = RegistrationForm::where('name', $form)->firstOrFail();
        $documentRequirements = DocumentRequirement::where('form_id', $formDetails->id)->get();

        return view('registration.form', compact('formDetails', 'documentRequirements'));
    }

    /**
     * Submit the registration form.
     */
    public function submitForm(Request $request, $form)
    {
        try {
            Log::info('Form submitted with data: ', $request->all());

            $formDetails = RegistrationForm::where('name', $form)->firstOrFail();

            // Validate the request
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'mobile' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|string|max:255',
                'testimonial_1' => 'nullable|string|max:255',
                'testimonial_2' => 'nullable|string|max:255',
                'membership_class' => 'required|string',
                'year_establishment' => 'required|integer|min:1800|max:' . date('Y'),
                'ntn' => 'nullable|string|max:50',
                'sales_tax_number' => 'nullable|string|max:50',
                'main_business' => 'required|string|max:255',
                'product_line' => 'nullable|string|max:255',
                'directors.*.name' => 'required|string|max:255',
                'directors.*.cnic_number' => 'required|string|max:15',
                'directors.*.relation' => 'required|string|max:50',
                'directors.*.date_of_birth' => 'required|date',
                'directors.*.gender' => 'required|string|in:male,female',
                'directors.*.home_address' => 'required|string|max:255',
                'directors.*.phone' => 'nullable|string|max:20',
                'documents.*' => 'file|mimes:pdf,jpg,png|max:2048',
            ]);

            // Create or find the user
            $user = User::firstOrCreate(
                [
                    'mobile_number' => $validated['mobile'],
                    'email' => $validated['email'],
                ],
                [
                    'name' => $validated['company_name'],
                    'password' => bcrypt('temporary-password'),
                    'role' => 'pending',
                ]
            );

            // Create the registration
            $registration = Registration::create([
                'user_id' => $user->id,
                'form_id' => $formDetails->id,
                'company_name' => $validated['company_name'],
                'address' => $validated['address'],
                'telephone' => $validated['telephone'],
                'mobile' => $validated['mobile'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'testimonial_1' => $validated['testimonial_1'],
                'testimonial_2' => $validated['testimonial_2'],
                'membership_class' => $validated['membership_class'],
                'year_establishment' => $validated['year_establishment'],
                'ntn' => $validated['ntn'],
                'sales_tax_number' => $validated['sales_tax_number'],
                'main_business' => $validated['main_business'],
                'product_line' => $validated['product_line'],
                'status' => 'pending', // Default status
            ]);

            // Save uploaded documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $path = $file->store('documents');
                    RegistrationDocument::create([
                        'registration_id' => $registration->id,
                        'document_type' => $request->input("document_names.$index"),
                        'document_path' => $path,
                    ]);
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('pdf.registration', compact('registration'));
            $pdfPath = 'pdfs/registration_' . $registration->id . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdf->output());

            return redirect()->route('home')->with('success', 'Registration submitted successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
        // Return back with errors
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        Log::error('Error during registration submission: ' . $e->getMessage());
        return redirect()->back()->withErrors('An error occurred while submitting the registration form. Please try again.');
    }
    }

    /**
     * Download the generated PDF.
     */
    public function downloadPDF($id)
    {
        $registration = Registration::findOrFail($id);
        $pdfPath = 'pdfs/registration_' . $registration->id . '.pdf';

        if (Storage::disk('public')->exists($pdfPath)) {
            return Storage::disk('public')->download($pdfPath);
        }

        return redirect()->back()->with('error', 'PDF not found.');
    }

    /**
     * Update Registration Status
     */
    private function updateRegistrationStatus($id, $newStatus, $successMessage)
    {
        $registration = Registration::findOrFail($id);
        $registration->update(['status' => $newStatus]);

        return redirect()->route('admin.dashboard')->with('success', $successMessage);
    }

    /**
     * Workflow Steps
     */
    public function verifyDocuments($id) { return $this->updateRegistrationStatus($id, 'fee_due', 'Documents verified. Fee payment required.'); }
    public function collectFee($id) { return $this->updateRegistrationStatus($id, 'fee_paid', 'Fee collected successfully.'); }
    public function auditDocuments($id) { return $this->updateRegistrationStatus($id, 'provisionally_approved', 'Documents audited successfully.'); }
    public function approveProvisionalMembership($id) { return $this->updateRegistrationStatus($id, 'committee_review', 'Provisional membership approved.'); }
    public function grantFinalApproval($id) { return $this->updateRegistrationStatus($id, 'final_approval', 'Membership approved.'); }
}

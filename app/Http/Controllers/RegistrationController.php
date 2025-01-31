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
            $formDetails = RegistrationForm::where('name', $form)->firstOrFail();

            // Validate the request
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'telephone' => 'nullable|string|max:20',
                'mobile' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|string|max:255',
                'membership_class' => 'required|string',
                'year_establishment' => 'required|integer|min:1800|max:' . date('Y'),
                'ntn' => 'nullable|string|max:50',
                'sales_tax_number' => 'nullable|string|max:50',
                'main_business' => 'required|string|max:255',
                'product_line' => 'nullable|string|max:255',
                'testimonial_1' => 'nullable|string|max:255',
                'testimonial_2' => 'nullable|string|max:255',
                'directors.*.name' => 'required|string|max:255',
                'directors.*.cnic_number' => 'required|string|max:15',
                'directors.*.relation' => 'required|string|max:50',
                'directors.*.date_of_birth' => 'required|date',
                'directors.*.gender' => 'required|string|in:male,female',
                'directors.*.home_address' => 'required|string|max:255',
                'directors.*.phone' => 'nullable|string|max:20',
                'directors.*.cnic_issue_date' => 'required|date',
		'directors.*.cnic_expiry_date' => 'required|date|after:directors.*.cnic_issue_date',
		'directors.*.cnic_front' => 'required|file|mimes:jpg,png,pdf|max:2048',
		'directors.*.cnic_back' => 'required|file|mimes:jpg,png,pdf|max:2048',
                'documents.*' => 'file|mimes:pdf,jpg,png|max:2048',
            ]);

            // Create User or Retrieve Existing
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

            // Create Registration
            $registration = Registration::create([
                'user_id' => $user->id,
                'form_id' => $formDetails->id,
                'company_name' => $validated['company_name'],
                'address' => $validated['address'],
                'telephone' => $validated['telephone'],
                'mobile' => $validated['mobile'],
                'email' => $validated['email'],
                'website' => $validated['website'],
                'membership_class' => $validated['membership_class'],
                'year_establishment' => $validated['year_establishment'],
                'ntn' => $validated['ntn'],
                'sales_tax_number' => $validated['sales_tax_number'],
                'main_business' => $validated['main_business'],
                'product_line' => $validated['product_line'],
                'testimonial_1' => $validated['testimonial_1'],
                'testimonial_2' => $validated['testimonial_2'],
                'status' => 'pending',
		'payment_status' => 'not_required', // Do not show in cashier dashboard yet
		'fee_amount' => $feeAmount, // Store correct fee
            ]);

	// Validate the request
        $validated = $request->validate([
            'directors.*.date_of_birth' => 'required|date_format:Y-m-d',
        ]);

        // Convert date format from DD/MM/YYYY to YYYY-MM-DD
        if ($request->has('directors')) {
            foreach ($request->input('directors') as $index => $director) {
                if (isset($director['date_of_birth'])) {
                    $validated['directors'][$index]['date_of_birth'] = date('Y-m-d', strtotime(str_replace('/', '-', $director['date_of_birth'])));
                }
            }
        }
// Save Directors/Partners
if ($request->has('directors')) {
    foreach ($request->input('directors') as $index => $director) {
        $directorData = $director;

        if ($request->hasFile("directors.$index.cnic_front")) {
            $directorData['cnic_front'] = $request->file("directors.$index.cnic_front")
                ->store('private/documents'); // ✅ Save inside correct path
        }
        if ($request->hasFile("directors.$index.cnic_back")) {
            $directorData['cnic_back'] = $request->file("directors.$index.cnic_back")
                ->store('private/documents'); // ✅ Save inside correct path
        }

        // ✅ Store director data in the database
        $registration->directorsPartners()->create($directorData);
    }
}

            // Save Uploaded Documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $documentName = $request->input("document_names.$index") ?? 'Unknown Document';

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

            // Redirect with success message & PDF download
            return redirect()->route('register.show', $form)->with([
                'success' => 'Registration submitted successfully!',
                'download_url' => asset('storage/' . $pdfPath)
            ]);

        } catch (\Exception $e) {
            \Log::error('Error during registration: ' . $e->getMessage());
            return back()->withErrors('An error occurred while submitting the registration form. Please try again.');
        }
    }

    /**
     * Download the generated PDF.
     */
public function downloadPDF($id)
{
    $registration = Registration::findOrFail($id);
    $pdfPath = 'pdfs/registration_' . $registration->id . '.pdf';

    if (!Storage::disk('public')->exists($pdfPath)) {
        return redirect()->back()->with('error', 'PDF not found.');
    }

    return response()->download(storage_path('app/public/' . $pdfPath));
}
    /**
     * Update Registration Status
     */
    private function updateRegistrationStatus($id, $newStatus, $successMessage, $additionalUpdates = [])
    {
        $registration = Registration::findOrFail($id);
        $updateData = array_merge(['status' => $newStatus], $additionalUpdates);
        $registration->update($updateData);

        return redirect()->route('admin.dashboard')->with('success', $successMessage);
    }

    /**
     * Workflow Steps
     */
    public function verifyDocuments($id) { return $this->updateRegistrationStatus($id, 'fee_due', 'Documents verified. Fee payment required.'); }
    
    public function collectFee($id) { 
        return $this->updateRegistrationStatus($id, 'fee_paid', 'Fee collected successfully.', [
            'fee_paid_at' => now(),
            'payment_status' => 'Paid'
        ]); 
    }

    public function auditDocuments($id) { return $this->updateRegistrationStatus($id, 'provisionally_approved', 'Documents audited successfully.'); }
    
    public function approveProvisionalMembership($id) { return $this->updateRegistrationStatus($id, 'committee_review', 'Provisional membership approved.'); }
    
    public function grantFinalApproval($id) { return $this->updateRegistrationStatus($id, 'final_approval', 'Membership approved.'); }
public function forwardToChairman($id)
{
    $registration = Registration::findOrFail($id);

    // Ensure only the secretary can forward the application
    if (Auth::user()->role !== 'dg_secretary') {
        return redirect()->route('home')->with('error', 'Unauthorized action.');
    }

    // Update application status
    $registration->update(['status' => 'pending_chairman_approval']);

    return redirect()->route('secretary.dashboard')->with('success', 'Application forwarded to Chairman for final approval.');
}
}

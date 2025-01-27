<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Registration;
use App\Models\RegistrationForm;
use App\Models\DocumentRequirement;
use App\Models\RegistrationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Show the registration form for the specified type.
     *
     * @param string $form
     * @return \Illuminate\View\View
     */
    public function showForm($form)
    {
        $formDetails = RegistrationForm::where('name', $form)->firstOrFail();
        $documentRequirements = DocumentRequirement::where('form_id', $formDetails->id)->get();

        return view('registration.form', [
            'formDetails' => $formDetails,
            'documentRequirements' => $documentRequirements,
        ]);
    }

    /**
     * Submit the registration form.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForm(Request $request, $form)
    {
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

        // Check if a user exists with the provided mobile or email
        $user = User::firstOrCreate(
            [
                'mobile_number' => $validated['mobile'],
                'email' => $validated['email'],
            ],
            [
                'name' => $validated['company_name'],
                'password' => bcrypt('temporary-password'), // Temporary password
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
        ]);

        // Save directors/partners
        if ($request->has('directors')) {
            foreach ($request->input('directors') as $director) {
                $registration->directorsPartners()->create($director);
            }
        }

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

        return redirect()->route('home')->with('success', 'Registration submitted successfully.');
    }

    /**
     * Workflow: Verify Documents
     */
    public function verifyDocuments($id)
    {
        $registration = Registration::findOrFail($id);

        if (Auth::user()->role !== 'membership_supervisor') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration->update(['status' => 'fee_due']);
        return redirect()->route('admin.dashboard')->with('success', 'Documents verified. Fee payment required.');
    }

    /**
     * Workflow: Collect Fee
     */
    public function collectFee($id)
    {
        $registration = Registration::findOrFail($id);

        if (Auth::user()->role !== 'cashier') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration->update(['status' => 'fee_paid']);
        return redirect()->route('admin.dashboard')->with('success', 'Fee collected successfully.');
    }

    /**
     * Workflow: Audit Documents
     */
    public function auditDocuments($id)
    {
        $registration = Registration::findOrFail($id);

        if (Auth::user()->role !== 'accounts_audit') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration->update(['status' => 'provisionally_approved']);
        return redirect()->route('admin.dashboard')->with('success', 'Documents audited successfully.');
    }

    /**
     * Workflow: Approve Provisional Membership
     */
    public function approveProvisionalMembership($id)
    {
        $registration = Registration::findOrFail($id);

        if (Auth::user()->role !== 'dg_secretary') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration->update(['status' => 'committee_review']);
        return redirect()->route('admin.dashboard')->with('success', 'Provisional membership approved.');
    }

    /**
     * Workflow: Grant Final Approval
     */
    public function grantFinalApproval($id)
    {
        $registration = Registration::findOrFail($id);

        if (Auth::user()->role !== 'chairman_president') {
            return redirect()->route('home')->with('error', 'Unauthorized action.');
        }

        $registration->update(['status' => 'final_approval']);
        return redirect()->route('admin.dashboard')->with('success', 'Membership approved.');
    }
}

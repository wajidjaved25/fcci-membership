<?php

namespace Database\Seeders;

use App\Models\RegistrationForm;
use App\Models\DocumentRequirement;
use Illuminate\Database\Seeder;

class RegistrationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forms = [
            ['name' => 'Proprietorship', 'description' => 'Registration for proprietorship companies.'],
            ['name' => 'Partnership', 'description' => 'Registration for partnership companies.'],
            ['name' => 'Limited Company', 'description' => 'Registration for limited companies.'],
        ];

        foreach ($forms as $form) {
            // Create the registration form
            $formModel = RegistrationForm::create($form);

            // Assign documents based on the form type
            $documents = match ($form['name']) {
                'Proprietorship' => ['National Tax Certificate', 'Bank Certificate'],
                'Partnership' => ['Partnership Deed', 'Tax Certificate'],
                'Limited Company' => ['Incorporation Certificate', 'Memorandum of Association'],
                default => [],
            };

            // Create document requirements
            foreach ($documents as $document) {
                DocumentRequirement::create([
                    'form_id' => $formModel->id,
                    'document_name' => $document,
                ]);
            }
        }
    }
}

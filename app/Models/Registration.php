<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'form_id',
        'company_name',
        'address',
        'telephone',
        'mobile',
        'email',
        'website',
        'testimonial_1',
        'testimonial_2',
        'membership_class',
        'year_establishment',
        'ntn',
        'sales_tax_number',
        'main_business',
        'product_line',
        'status',
        'rejection_reason',
    ];

    /**
     * Relationship with User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with RegistrationForm.
     */
    public function form()
    {
        return $this->belongsTo(RegistrationForm::class, 'form_id');
    }

    /**
     * Relationship with Directors/Partners.
     */
    public function directorsPartners()
    {
        return $this->hasMany(DirectorPartner::class, 'registration_id');
    }

    /**
     * Relationship with Registration Documents.
     */
    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class, 'registration_id');
    }
}

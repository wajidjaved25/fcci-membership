<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequirement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'form_id',
        'document_name',
    ];

    /**
     * Get the registration form that this document requirement belongs to.
     */
    public function form()
    {
        return $this->belongsTo(RegistrationForm::class, 'form_id');
    }
}

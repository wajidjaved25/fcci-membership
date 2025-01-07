<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'name',
        'cnic_number',
        'relation',
        'date_of_birth',
        'gender',
        'home_address',
        'phone',
    ];

    /**
     * Relationship with Registration.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cnic_number',
        'relation',
        'date_of_birth',
        'gender',
        'home_address',
        'phone',
        'registration_id',
	'cnic_issue_date',
	'cnic_expiry_date',
	'cnic_front',
	'cnic_back',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}

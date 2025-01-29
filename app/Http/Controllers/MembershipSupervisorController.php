<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;

class MembershipSupervisorController extends Controller
{
    public function index()
    {
        $registrations = Registration::where('status', 'pending')->get();
        return view('supervisor.dashboard', compact('registrations'));
    }
}
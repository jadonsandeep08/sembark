<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Show the Admin invitation form.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();

        return view('superadmin.invitations.create', compact('companies'));
    }

    /**
     * Store an Admin invitation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => [
                'required',
                'exists:companies,id',
            ],
            'email' => [
                'required',
                'email',
            ],
        ]);

        Invitation::create([
            'company_id' => $validated['company_id'],
            'invited_by' => auth()->id(),
            'email' => $validated['email'],
            'role' => 'admin',
            'token' => Str::random(64),
            'expires_at' => now()->addDays(2),
        ]);

        return redirect()
            ->route('superadmin.invitations.create')
            ->with('success', 'Admin invitation created successfully.');
    }
}
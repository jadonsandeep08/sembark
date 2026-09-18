<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function create()
    {
        return view('admin.invitations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', Rule::in(['admin', 'member'])],
        ]);

        if (User::where('email', $validated['email'])->exists()) {
            return back()->withErrors(['email' => 'A user with this email already exists.'])->withInput();
        }

        Invitation::where('email', $validated['email'])
            ->where('company_id', $request->user()->company_id)
            ->whereNull('accepted_at')
            ->delete();

        $invitation = Invitation::create([
            'company_id' => $request->user()->company_id,
            'invited_by' => $request->user()->id,
            'email' => $validated['email'],
            'role' => $validated['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(2),
        ]);

        return redirect()
            ->route('admin.invitations.create')
            ->with('success', 'Invitation created successfully.')
            ->with('invitation_url', route('invitations.show', $invitation->token));
    }
}

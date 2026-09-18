<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InvitationAcceptController extends Controller
{
    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            abort(410, 'This invitation has expired.');
        }

        return view('invitations.accept', compact('invitation'));
    }

    public function accept(Request $request, string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->firstOrFail();

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            abort(410, 'This invitation has expired.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (User::where('email', $invitation->email)->exists()) {
            return back()->withErrors([
                'email' => 'A user with this email already exists.',
            ]);
        }

        DB::transaction(function () use ($invitation, $validated) {
            User::create([
                'company_id' => $invitation->company_id,
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role' => $invitation->role,
            ]);

            $invitation->update([
                'accepted_at' => now(),
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Account created successfully. You can now log in.');
    }
}
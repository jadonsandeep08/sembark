<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Show the create company form.
     */
    public function create()
    {
        return view('superadmin.companies.create');
    }

    /**
     * Store a new company.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        Company::create([
            'name' => $validated['name'],
        ]);

        return redirect()
            ->route('superadmin.companies.create')
            ->with('success', 'Company created successfully.');
    }
}
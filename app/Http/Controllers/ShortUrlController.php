<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = ShortUrl::with(['company', 'user'])->latest();

        if ($user->role === 'admin') {
            $query->where('company_id', $user->company_id);
        } elseif ($user->role === 'member') {
            $query->where('user_id', $user->id);
        }

        $shortUrls = $query->get();

        return view('short-urls.index', compact('shortUrls'));
    }

    public function create()
    {
        return view('short-urls.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'super_admin') {
            abort(403, 'SuperAdmin cannot create short URLs.');
        }

        $validated = $request->validate([
            'original_url' => ['required', 'url', 'max:2048'],
        ]);

        do {
            $shortCode = Str::random(7);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
        ]);

        return redirect()->route('short-urls.index')->with('success', 'Short URL created successfully.');
    }

    public function redirect(string $shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}

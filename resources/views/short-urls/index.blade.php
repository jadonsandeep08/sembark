<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Short URLs</title></head>
<body>
<h1>Short URLs</h1>
@if (session('success')) <p>{{ session('success') }}</p> @endif
@if (auth()->user()->role !== 'super_admin') <p><a href="{{ route('short-urls.create') }}">Create Short URL</a></p> @endif
<table border="1" cellpadding="8">
<thead><tr><th>Short URL</th><th>Original URL</th><th>Company</th><th>Created By</th></tr></thead>
<tbody>
@forelse ($shortUrls as $shortUrl)
<tr>
<td><a href="{{ route('short-urls.redirect', $shortUrl->short_code) }}" target="_blank">{{ route('short-urls.redirect', $shortUrl->short_code) }}</a></td>
<td>{{ $shortUrl->original_url }}</td><td>{{ $shortUrl->company->name }}</td><td>{{ $shortUrl->user->name }}</td>
</tr>
@empty <tr><td colspan="4">No short URLs found.</td></tr> @endforelse
</tbody></table>
<p><a href="{{ route('dashboard') }}">Back to Dashboard</a></p>
</body></html>

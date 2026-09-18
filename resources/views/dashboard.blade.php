<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Dashboard</title></head>
<body>
<h1>Dashboard</h1>
<p>Welcome, {{ auth()->user()->name }}</p>
<p>Role: {{ auth()->user()->role }}</p>
@if (auth()->user()->company) <p>Company: {{ auth()->user()->company->name }}</p> @endif

<ul>
    <li><a href="{{ route('short-urls.index') }}">View Short URLs</a></li>
    @if (auth()->user()->role === 'super_admin')
        <li><a href="{{ route('superadmin.companies.create') }}">Create Company</a></li>
        <li><a href="{{ route('superadmin.invitations.create') }}">Invite Company Admin</a></li>
    @elseif (auth()->user()->role === 'admin')
        <li><a href="{{ route('admin.invitations.create') }}">Invite Admin or Member</a></li>
        <li><a href="{{ route('short-urls.create') }}">Create Short URL</a></li>
    @else
        <li><a href="{{ route('short-urls.create') }}">Create Short URL</a></li>
    @endif
</ul>

<form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
</body>
</html>

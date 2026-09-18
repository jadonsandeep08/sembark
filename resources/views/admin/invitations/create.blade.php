<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Invite User</title></head>
<body>
<h1>Invite User</h1>
<p>Company: {{ auth()->user()->company->name }}</p>
@if (session('success')) <p>{{ session('success') }}</p> @endif
@if (session('invitation_url'))
    <p>Invitation link: <a href="{{ session('invitation_url') }}">{{ session('invitation_url') }}</a></p>
@endif
@if ($errors->any()) <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul> @endif
<form method="POST" action="{{ route('admin.invitations.store') }}">
    @csrf
    <div><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" required></div><br>
    <div><label for="role">Role</label><select id="role" name="role" required><option value="admin" @selected(old('role') === 'admin')>Admin</option><option value="member" @selected(old('role') === 'member')>Member</option></select></div><br>
    <button type="submit">Create Invitation</button>
</form>
<p><a href="{{ route('dashboard') }}">Back to Dashboard</a></p>
</body>
</html>

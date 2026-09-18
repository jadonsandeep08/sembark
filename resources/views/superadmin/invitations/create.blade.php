<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Admin</title>
</head>
<body>

    <h1>Invite Company Admin</h1>

    <p>
        Logged in as:
        {{ auth()->user()->name }}
        ({{ auth()->user()->role }})
    </p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('invitation_url'))
        <p>Invitation link: <a href="{{ session('invitation_url') }}">{{ session('invitation_url') }}</a></p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST"
          action="{{ route('superadmin.invitations.store') }}">

        @csrf

        <div>
            <label for="company_id">Company</label>

            <select name="company_id" id="company_id" required>
                <option value="">Select Company</option>

                @foreach ($companies as $company)
                    <option
                        value="{{ $company->id }}"
                        {{ old('company_id') == $company->id ? 'selected' : '' }}
                    >
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label for="email">Admin Email</label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </div>

        <br>

        <button type="submit">
            Create Admin Invitation
        </button>

    </form>

    <br>

    <a href="{{ route('dashboard') }}">Back to Dashboard</a>

</body>
</html>
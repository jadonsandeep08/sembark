<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation</title>
</head>
<body>

    <h1>Accept Invitation</h1>

    <p>
        You have been invited to join
        <strong>{{ $invitation->company->name }}</strong>
        as <strong>{{ ucfirst($invitation->role) }}</strong>.
    </p>

    <p>
        Email: <strong>{{ $invitation->email }}</strong>
    </p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form
        method="POST"
        action="{{ route('invitations.accept', $invitation->token) }}"
    >
        @csrf

        <div>
            <label for="name">Name</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <br>

        <div>
            <label for="password_confirmation">Confirm Password</label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
            >
        </div>

        <br>

        <button type="submit">Create Account</button>
    </form>

</body>
</html>
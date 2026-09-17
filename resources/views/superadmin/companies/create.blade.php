<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Company</title>
</head>

<body>

    <h1>Create Company</h1>

    <p>
        Logged in as:
        {{ auth()->user()->name }}
        ({{ auth()->user()->role }})
    </p>

    {{-- Success message --}}
    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Create Company Form --}}
    <form method="POST" action="{{ route('superadmin.companies.store') }}">

        @csrf

        <div>
            <label for="name">Company Name</label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </div>

        <br>

        <button type="submit">
            Create Company
        </button>

    </form>

    <br>

    <a href="{{ route('dashboard') }}">
        Back to Dashboard
    </a>

</body>
</html>
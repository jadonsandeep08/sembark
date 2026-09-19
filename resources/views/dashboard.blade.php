<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sembark</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .header {
            background: #1f2937;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 35px auto;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-top: 0;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 9px 14px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout-btn {
            border: 0;
            background: #dc2626;
            color: white;
            padding: 9px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .role {
            color: #6b7280;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>Sembark URL Shortener</h2>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="logout-btn" type="submit">Logout</button>
    </form>
</div>

<div class="container">

    <div class="welcome">
        <h1>Welcome, {{ auth()->user()->name }}</h1>

        <p class="role">
            Role:
            {{ ucwords(str_replace('_', ' ', auth()->user()->role)) }}
        </p>

        @if(auth()->user()->company)
            <p>
                Company: {{ auth()->user()->company->name }}
            </p>
        @endif
    </div>

    <div class="cards">

        {{-- SUPER ADMIN DASHBOARD --}}
        @if(auth()->user()->role === 'super_admin')

            <div class="card">
                <h3>Companies</h3>
                <p>Create and manage companies.</p>

                <a href="{{ route('superadmin.companies.create') }}">
                    Create Company
                </a>
            </div>

            <div class="card">
                <h3>Invite Admin</h3>
                <p>Invite an Admin into a company.</p>

                <a href="{{ route('superadmin.invitations.create') }}">
                    Invite Admin
                </a>
            </div>

            <div class="card">
                <h3>All Short URLs</h3>
                <p>View short URLs from all companies.</p>

                <a href="{{ route('short-urls.index') }}">
                    View URLs
                </a>
            </div>

        @endif


        {{-- ADMIN DASHBOARD --}}
        @if(auth()->user()->role === 'admin')

            <div class="card">
                <h3>Create Short URL</h3>
                <p>Create a new short URL.</p>

                <a href="{{ route('short-urls.create') }}">
                    Create URL
                </a>
            </div>

            <div class="card">
                <h3>Company URLs</h3>
                <p>View URLs created within your company.</p>

                <a href="{{ route('short-urls.index') }}">
                    View URLs
                </a>
            </div>

            <div class="card">
                <h3>Invite User</h3>
                <p>Invite another Admin or Member.</p>

                <a href="{{ route('admin.invitations.create') }}">
                    Invite User
                </a>
            </div>

        @endif


        {{-- MEMBER DASHBOARD --}}
        @if(auth()->user()->role === 'member')

            <div class="card">
                <h3>Create Short URL</h3>
                <p>Create your own short URL.</p>

                <a href="{{ route('short-urls.create') }}">
                    Create URL
                </a>
            </div>

            <div class="card">
                <h3>My Short URLs</h3>
                <p>View the short URLs created by you.</p>

                <a href="{{ route('short-urls.index') }}">
                    My URLs
                </a>
            </div>

        @endif

    </div>

</div>

</body>
</html>
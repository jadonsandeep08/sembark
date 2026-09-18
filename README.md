# Sembark URL Shortener

A small multi-company URL shortener built with Laravel 10. The application has three roles: SuperAdmin, Admin and Member.

## Requirements

- PHP 8.1+
- Composer
- MySQL/MariaDB or SQLite

## Setup

1. Clone the repository and enter the project directory.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and configure the database.
4. Run `php artisan key:generate`.
5. Run `php artisan migrate --seed`.
6. Start the application with `php artisan serve`.

The seed creates the initial SuperAdmin account. For local development, check `database/seeders/SuperAdminSeeder.php` and change the seeded credentials before using the application outside a local environment.

## Roles and permissions

**SuperAdmin** can create companies, invite the first Admin for a company and view short URLs from every company. SuperAdmin cannot create short URLs.

**Admin** belongs to one company. An Admin can invite another Admin or Member into that same company, create short URLs and view all short URLs created inside the company.

**Member** belongs to one company. A Member can create short URLs and can only view URLs that they created.

## Invitation flow

Invitations contain the company, invited email, role, inviter, a random token and an expiry time. The invitation acceptance page is public because the invited person does not have an account yet. On acceptance, the user is created in the invitation's company and the invitation is marked as accepted.

For this coding exercise the generated invitation URL is displayed after an invitation is created. A production application could send the same link through email.

## Short URLs

Admin and Member users can submit a valid original URL. The application generates a unique seven-character code. Public URLs use `/s/{shortCode}` and redirect to the stored original URL.

## Tests

The feature tests cover company/admin invitation, invitation acceptance, Admin invitations, URL creation permissions, role-based URL visibility and public redirects.

Run:

```bash
php artisan test
```

The test environment uses an in-memory SQLite database as configured in `phpunit.xml`.

## Main files

- `app/Http/Controllers/SuperAdmin/CompanyController.php`
- `app/Http/Controllers/SuperAdmin/InvitationController.php`
- `app/Http/Controllers/Admin/InvitationController.php`
- `app/Http/Controllers/InvitationAcceptController.php`
- `app/Http/Controllers/ShortUrlController.php`
- `app/Http/Middleware/RoleMiddleware.php`
- `routes/web.php`
- `tests/Feature/UrlShortenerTest.php`

## AI tool disclosure

AI assistance was used for syntax guidance, debugging support and reviewing implementation ideas. The project structure, implementation decisions and final code were reviewed and tested as part of the development process.

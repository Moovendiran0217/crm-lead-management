# CRM Lead Management System

Laravel API for managing insurance and finance sales leads, assignments, status transitions, and follow-ups.

## Requirements

- PHP 8.2+
- Composer
- SQLite, MySQL, or PostgreSQL
- Node.js and npm (only needed for the bundled frontend assets)

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Configure `DB_*` in `.env`. For SQLite, create `database/database.sqlite` and set `DB_CONNECTION=sqlite`.

```bash
php artisan migrate --seed
php artisan serve
```

The API is available at `http://127.0.0.1:8000/api`.

Seeded accounts:

| Role | Email | Password |
| --- | --- | --- |
| ADMIN | admin@example.com | password |
| SALES | sales1@example.com | password |

## Authentication

`POST /api/login` returns a Laravel Sanctum bearer token. Send it on protected requests with `Authorization: Bearer <token>`.

## API Endpoints

All endpoints below except login require authentication.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| POST | `/api/login` | Authenticate a user |
| POST | `/api/logout` | Revoke the current token |
| GET | `/api/me` | Get the authenticated user |
| GET | `/api/dashboard` | Counts by lead status |
| GET | `/api/leads` | Paginated lead list |
| POST | `/api/leads` | Create a lead |
| GET | `/api/leads/{id}` | View a lead and its follow-ups |
| PUT | `/api/leads/{id}` | Update a lead |
| DELETE | `/api/leads/{id}` | Delete a lead (admin only) |
| POST | `/api/leads/{id}/followups` | Create a follow-up |
| GET | `/api/leads/{id}/followups` | List follow-ups |
| PUT | `/api/followups/{id}` | Update a follow-up |

Lead listing supports `search`, `status`, `source`, `assigned_to`, `page`, and `per_page` query parameters. `per_page` is limited to 100.

## Business Rules

- Leads may only be assigned to active `SALES` users. Sales users are automatically assigned their own new leads.
- An email cannot have more than one active lead. Active statuses are `NEW` and `FOLLOW_UP`.
- Allowed transitions are `NEW -> CONTACTED`, `NEW -> LOST`, `CONTACTED -> FOLLOW_UP`, `CONTACTED -> LOST`, `FOLLOW_UP -> CONTACTED`, `FOLLOW_UP -> CONVERTED`, and `FOLLOW_UP -> LOST`.
- `CONVERTED` and `LOST` leads cannot receive new follow-ups. Converted leads cannot be edited or deleted.
- Follow-up dates cannot be in the past.
- Admins can view and manage all leads. Sales users can view and update only assigned leads.

## Testing

```bash
php artisan test
```

The feature suite covers lead creation, duplicate prevention, assignment validation, status protection, converted-lead deletion protection, and follow-up protection.

## Postman

Import `docs/crm-lead-management.postman_collection.json`. Set `base_url` to the running API URL, run Login first, and store the returned token in the `token` collection variable.

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

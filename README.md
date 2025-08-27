# Back Movie Quotes

A Laravel-based backend for a movie quotes platform, supporting movie and quote management, notifications, user authentication, and more.

## Features

-   Movie and quote CRUD operations
-   User registration, login (email/username), Google OAuth
-   Notifications for quote likes and comments
-   RESTful API endpoints for all resources
-   Session-based authentication (Sanctum)
-   Event broadcasting (Pusher)
-   Database seeding for categories, movies, quotes, and users
-   Feature tests (Pest)

## Tech Stack

-   PHP 8.x
-   Laravel 12.x
-   MySQL
-   Composer
-   Pest (testing)
-   Sanctum (API authentication)
-   Pusher (broadcasting)

## Project Structure

```
back-movie-quotes-akaki-goginava/
├── app/
│   ├── Models/
│   ├── Http/
│   ├── Notifications/
│   ├── Policies/
│   ├── Providers/
│   └── ...
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── public/
├── resources/
│   ├── views/
│   └── ...
├── routes/
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

## Database Schema

The MySQL database schema for Movie Quotes is visualized and maintained using [DrawSQL](https://drawsql.app/).

You can view the schema diagram here:

[Database Design Diagram](readme/assets/movie-quotes-db-diagram.png)

## Getting Started

### Prerequisites

-   PHP >= 8.1
-   Composer
-   MySQL

### Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/RedberryInternship/quizwiz-back-akaki-goginava.git
    cd back-movie-quotes-akaki-goginava
    ```
2. Install PHP dependencies:
    ```bash
    composer install
    ```
3. Install JS dependencies:
    ```bash
    npm install
    ```
4. Build frontend assets:
    ```bash
    npm run build
    ```
5. Copy the example environment file and configure it:
    ```bash
    cp .env.example .env
    ```
6. Generate application key:
    ```bash
    php artisan key:generate
    ```
7. Run migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```
8. Link storage:
    ```bash
    php artisan storage:link
    ```
9. Optimize the application:
    ```bash
    php artisan optimize
    ```

## Environment Configuration

-   `.env` — main environment file for local/dev
-   `.env.testing` — used for tests

**Important:**

-   Set correct `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in your `.env`.
-   Configure `MAIL_*` variables for email notifications.
-   Set `APP_URL` and configure `config/cors.php` for allowed origins.
-   Set up `SANCTUM_STATEFUL_DOMAINS` for SPA authentication (should match your frontend domain, e.g. `app.local.test:5173`).
-   Configure Google OAuth:
    -   Set `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, and `GOOGLE_REDIRECT_URI` in your `.env`.
    -   Make sure `GOOGLE_REDIRECT_URI` matches the callback URL set in your Google Cloud Console.
-   Set `SESSION_DOMAIN` in your `.env` to match your app domain (e.g. `local.test`) for proper session cookie handling across subdomains.

## Running Tests

Run all tests with:

```bash
php artisan test
```

## API Overview

-   All endpoints are under `/api/`
-   Auth: `/api/register`, `/api/login`, `/api/google`, `/api/logout`
-   Movies: `/api/movies`
-   Quotes: `/api/quotes`
-   Notifications: `/api/notifications`
-   Supports filtering

## Seeding

-   Categories, movies, quotes, and users are seeded via:
    ```bash
    php artisan db:seed
    ```
-   To seed only categories:
    ```bash
    php artisan db:seed --class=CategorySeeder
    ```

## Notifications

-   Users receive notifications for quote likes and comments.
-   Notifications are broadcasted via Pusher.

## Email Templates

-   Email templates are located in `resources/views/email/`.

## Queue Worker & Broadcasting

To enable broadcasting of notifications and events (e.g., quote likes/comments), you must run a queue worker:

```bash
php artisan queue:work
```

This should be running in the background on your server or local machine. For production, consider using a process manager like Supervisor or PM2 to keep the worker alive.

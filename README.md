# BlogBB

A modern blog platform built with Laravel.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=SettingsSeeder
```

## Features

-   Create and view blog posts
-   Search functionality
-   IP banning
-   Admin panel
-   Maintenance mode

## Usage

-   Homepage: `/`
-   All posts: `/blogs`
-   Create post: `/post`
-   Admin: `/admin`

## License

MIT

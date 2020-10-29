<p align="center">
<img src="/github_image/header_image.jpg?raw=true" alt="Bundesliga Elo Ranking" width="250">
</p>

# Bundesliga Elo Ranking

The Bundesliga Elo ranking.

The elo value is calculated by the formula which is used to calculate the [FIFA world ranking](https://de.wikipedia.org/wiki/FIFA-Weltrangliste#Berechnungsmethode). 

## Installation

Install all dependencies with:

```bash
composer install
```
Add the database connection to the .env file and run the migration:

```bash
php artisan migrate
```

Add the bundesliga data (db_dump/bundesliga.sql) to the database.

## Usage

Run the following command to calculate the elo ranking (may take up to an hour or longer):

```bash
php artisan bundesliga:calculate
```

## Visualization

The first visualization was done with [barchartrace](https://github.com/FabDevGit/barchartrace)

More visualizations will be done with flourish studio.

<p align="center">
<img src="/github_image/2k00s.jpg?raw=true" alt="Bundesliga Elo Ranking" width="800">
</p>

Available visualizations:

[Bundesliga alltime (clubs with more than 1500 played games)](https://public.flourish.studio/visualisation/4172740/)

[Bundesliga 60ies](https://public.flourish.studio/visualisation/4159809/) 

[Bundesliga 70ies](https://public.flourish.studio/visualisation/4165418/) 

[Bundesliga 80ies](https://public.flourish.studio/visualisation/4167137/) 

[Bundesliga 90ies](https://public.flourish.studio/visualisation/4171797/) 

[Bundesliga 2k00s](https://public.flourish.studio/visualisation/4171855/) 

[Bundesliga 2k10s](https://public.flourish.studio/visualisation/4171899/) 

## TODO

1) Add more visualizations with [flourish studio](https://flourish.studio/) 

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


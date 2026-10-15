# Cloth API

REST API for a clothing store, built with Laravel and MySQL.

## Features
- Categories and products (one-to-many)
- Token authentication with Laravel Sanctum
- Orders with order items and stock handling
- Validation, pagination, factory and seeder

## Setup
1. composer install
2. Copy .env.example to .env and set your database
3. php artisan key:generate
4. php artisan migrate --seed
5. php artisan serve

## Endpoints
| Method | URL | Auth |
|---|---|---|
| POST | /api/register | No |
| POST | /api/login | No |
| POST | /api/logout | Yes |
| GET | /api/products | No |
| GET | /api/products/{id} | No |
| POST/PATCH/DELETE | /api/products | Yes |
| GET | /api/categories | No |
| GET | /api/orders | Yes |
| POST | /api/orders | Yes |

Send Accept: application/json. For protected routes also send Authorization: Bearer <token>.

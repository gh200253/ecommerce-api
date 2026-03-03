# Enterprise E-Commerce RESTful API

A robust, scalable, and secure E-Commerce RESTful API built with Laravel 11. This project demonstrates enterprise-level backend architecture, emphasizing security, optimal database performance, and clean code principles.

## System Architecture & Features

* **Authentication & Security:** JWT-like stateless authentication using Laravel Sanctum.
* **Role-Based Access Control (RBAC):** Implementation of Spatie Laravel-Permission to establish strict boundaries between `Admin` and `Customer` privileges.
* **Advanced Data Filtering:** Dynamic product filtering (by category, price range, and search terms) utilizing optimized Eloquent queries.
* **Asset Management:** Secure multipart file uploading, validation, and local storage handling for product images.
* **Asynchronous Processing:** Background job processing using Laravel Queues for non-blocking operations, such as sending order receipt emails.
* **Payment Gateway Integration:** A dedicated webhook receiver to handle simulated external payment gateway callbacks and automatically update order statuses.

## Technology Stack

* **Framework:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL / PostgreSQL
* **Authentication:** Laravel Sanctum
* **Authorization:** Spatie Laravel-Permission
* **Testing & Documentation:** Postman

## API Endpoints Overview

### Public Routes (No Auth Required)
* `POST /api/register` - Register a new customer
* `POST /api/login` - Authenticate user and receive token
* `GET /api/categories` - List all categories
* `GET /api/products` - List all products (supports query parameters: `search`, `category_id`, `min_price`, `max_price`)
* `GET /api/products/{id}` - Retrieve a single product
* `POST /api/webhooks/payment` - Handle external payment success/failure callbacks

### Protected Routes (Requires Bearer Token)
* `POST /api/logout` - Revoke current token
* `GET /api/user` - Retrieve authenticated user profile
* `POST /api/checkout` - Place a new order
* `GET /api/orders` - Retrieve order history for the authenticated user

### Admin Only Routes (Requires Admin Role + Bearer Token)
* `POST /api/categories` - Create a new category
* `POST /api/products` - Create a new product (supports `multipart/form-data` for images)
* `PUT /api/products/{id}` - Update an existing product
* `DELETE /api/products/{id}` - Delete a product

## Local Setup Instructions

1. Clone the repository and install dependencies:
    ```bash
    composer install
    ```
2. Configure environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
3. Run database migrations and seeders (initializes roles and admin user):
    ```bash
    php artisan migrate --seed
    ```
4. Create the symbolic link for accessible storage:
    ```bash
    php artisan storage:link
    ```
5. Start the application and queue worker:
    ```bash
    php artisan queue:work
    php artisan serve
    ```

## Deployment (Render.com)

- Web Service: Laravel 11 + PostgreSQL
- Queue Worker: `php artisan queue:work`
- Storage: `php artisan storage:link` run on deploy
- Environment: `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=pgsql`, `QUEUE_CONNECTION=database`, `MAIL_*` for email jobs

## Postman Documentation

[View API collection on Postman](https://ecocollect.postman.co/workspace/eCommerce-api~07ed5c9f-0407-43ac-881a-2c343151d6b4/collection/33859224-43616472-669f-4149-a2c2-41c1757b42f6?action=share&creator=33859224)

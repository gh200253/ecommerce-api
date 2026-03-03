FROM php:8.2-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install zip pdo pdo_pgsql pdo_sqlite

# Workdir
WORKDIR /app

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# (Optional) SQLite file if you ever use sqlite locally in this image
RUN touch database/database.sqlite || true

# Run migrations and create storage symlink
RUN php artisan migrate --force && php artisan storage:link

# Start Laravel dev server (Render will inject $PORT)
CMD php artisan serve --host=0.0.0.0 --port=$PORT


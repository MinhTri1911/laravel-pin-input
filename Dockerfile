# Base image
FROM php:8.2-apache

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-enable pdo_mysql
# Enable Apache modules
RUN a2enmod rewrite

RUN php -m

# Copy composer.lock and composer.json
# Copy the rest of the application files
COPY ./src ./

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader


# Generate key
#RUN php artisan key:generate
#RUN php artisan migrate

# Install NPM dependencies and build assets
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs
RUN npm add -D typescript && npm add -D @vue/tsconfig
RUN npm install

# Set ownership permissions
RUN chown -R www-data:www-data /app/storage

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]

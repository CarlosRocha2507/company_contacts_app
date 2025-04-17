# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache 

# Set the working directory inside the container
WORKDIR /var/www/html

# Update packages and install PHP extensions
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y libzip-dev unzip && \
    docker-php-ext-install pdo pdo_mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application files into the container
COPY ./app /var/www/html

# Set the correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Expose Apache's port 80
EXPOSE 80  

# Enable Apache's mod_rewrite (required for Laravel and other frameworks)
RUN a2enmod rewrite

# Start Apache in the foreground
CMD ["apache2-foreground"]

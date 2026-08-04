FROM php:8.2-apache

# Install system dependencies and enable required PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli gd zip

# Enable Apache mod_rewrite and headers
RUN a2enmod rewrite headers
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory and copy application files
WORKDIR /var/www/html
COPY . /var/www/html/

# Ensure uploads and data directories are writable by web server
RUN mkdir -p uploads/berita uploads/editor uploads/perangkat data \
    && chown -R www-data:www-data /var/www/html/uploads /var/www/html/data \
    && chmod -R 775 /var/www/html/uploads /var/www/html/data

# Change Apache listening port to 8081 for host network mode compatibility
RUN sed -i 's/80/8081/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 8081

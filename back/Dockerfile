FROM php:8.1-fpm

ENV USER=www
ENV GROUP=www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Postgre MONGODB
RUN apt-get update && apt-get install -y && pecl install mongodb apcu && docker-php-ext-enable mongodb apcu opcache

# Setup working directory
WORKDIR /var/www/html/crmvoxBack/

# Create User and Group
RUN groupadd -g 1000 ${GROUP} && useradd -u 1000 -ms /bin/bash -g ${GROUP} ${USER}

# Grant Permissions
RUN chown -R ${USER} /var/www/html/crmvoxBack/

# Select User
# USER ${USER}

# Copy permission to selected user
COPY --chown=${USER}:${GROUP} . .

RUN cd /var/www/html/crmvoxBack/
RUN composer install

EXPOSE 8000

CMD php artisan serve --host 0.0.0.0
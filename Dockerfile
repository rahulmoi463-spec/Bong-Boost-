FROM php:8.2-apache

# PostgreSQL ড্রাইভার ও প্রয়োজনীয় প্যাকেজ ইনস্টল করা
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Apache Mod-Rewrite এনাবল করা
RUN a2enmod rewrite

# প্রজেক্টের সব ফাইল সার্ভারে কপি করা
COPY . /var/www/html/

# পোর্ট এক্সপোজ করা
EXPOSE 80

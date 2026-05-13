FROM php:8.2-apache

COPY src/ /var/www/html/

RUN mkdir -p /app/uploads /app/logs \
    && chown -R www-data:www-data /app \
    && chmod -R 777 /app

EXPOSE 80
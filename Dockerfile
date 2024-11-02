FROM php:8.2-apache

# Engedélyezzük az mod_rewrite modult
RUN a2enmod rewrite

# Telepítjük a Composer-t
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Laravel fájlok másolása
COPY . /var/www/html

# Jogosultságok beállítása
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Dokumentum gyökér beállítása
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Apache konfiguráció frissítése
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf && \
    a2enconf laravel

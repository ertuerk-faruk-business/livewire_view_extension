FROM php:8.0-apache-buster

# Define build arguments
ARG USER_ID
ARG GROUP_ID

# Define environment variables
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APACHE_RUN_USER=vvuser
ENV APACHE_RUN_GROUP=vvuser
ENV USER_ID=$USER_ID
ENV GROUP_ID=$GROUP_ID
ENV USER_ID=${USER_ID:-1001}
ENV GROUP_ID=${GROUP_ID:-1001}

# Add group and user based on build arguments
RUN addgroup --gid ${GROUP_ID} ${APACHE_RUN_GROUP}
RUN adduser --disabled-password --gecos '' --uid ${USER_ID} --gid ${GROUP_ID} ${APACHE_RUN_USER}

# Set user and group of working directory
RUN chown -R vvuser:vvuser /var/www/html

# Set apache document root
RUN mkdir ${APACHE_DOCUMENT_ROOT}
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set Apache server name
RUN echo 'ServerName lve.localhost' >> /etc/apache2/apache2.conf

# Activate mod_rewrite
RUN a2enmod rewrite

# Update all packages
RUN apt-get update -y

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Install XDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug


# Restart apache
RUN apachectl restart

# Add alias for artisan
RUN echo "alias artisan='php artisan'" >> /home/vvuser/.bashrc

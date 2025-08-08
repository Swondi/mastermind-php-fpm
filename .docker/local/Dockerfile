FROM phpdockerio/php:8.3-fpm

WORKDIR "/var/www/myapp"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y install php8.3-intl php8.3-mbstring php8.3-pgsql php8.3-mysql php8.3-xdebug cron nginx supervisor && \
    rm -rf /var/lib/apt/lists/*
    
RUN mkdir -p /var/log/supervisor

# Copies project as www-data
COPY --chown=www-data:www-data . .

# Copies nginx config and supervisor config
COPY .docker/local/nginx.conf /etc/nginx/sites-available/default
COPY .docker/local/php-ini-overrides.ini /etc/php/8.3/fpm/conf.d/99-overrides.ini
COPY .docker/local/supervisord.conf /etc/supervisor.conf

# Sets up the entrypoint of the container
COPY .docker/local/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Start both Nginx + Symfony in parallel
ENTRYPOINT ["/entrypoint.sh"]
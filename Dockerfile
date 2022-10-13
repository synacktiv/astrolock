FROM php:8.1.11-alpine

# Install dependencies
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd xdebug imagick
RUN apk add bash curl && \
    curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash && \
    apk add symfony-cli

COPY . /var/www/astrolock
WORKDIR /var/www/astrolock

# Run migrations
RUN php bin/console doctrine:migrations:migrate

EXPOSE 8000
CMD ["symfony", "server:start"]
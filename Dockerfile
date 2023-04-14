FROM php:7

WORKDIR /app
VOLUME /app


RUN docker-php-ext-install mysqli pdo_mysql


# Expose the port
EXPOSE 8080

# Start PHP built-in webserver
CMD [ "php", "-S", "0.0.0.0:8080", "-t", "public" ]

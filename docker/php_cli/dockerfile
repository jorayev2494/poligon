FROM phpdockerio/php80-cli:latest

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff

RUN apt-get update \
    && apt-get -y --no-install-recommends install imagemagick php8.0-imagick php8.0-mysql php8.0-pgsql nano php8.0-gd php8.0-redis php8.0-bcmath php8.0-zmq \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/project
# RUN rm -rf /var/project/docker && chmod -R 777 /var/project/bootstrap && chmod -R 777 /var/project/storage

FROM php:8.0-fpm-alpine3.12
WORKDIR /var/www/
ARG USER=nemesis
ARG ID=1000

COPY composer.json composer.lock /var/www/


 # Adding user
RUN adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)" \
    --no-create-home \
    --uid ${ID} \
    ${USER}


USER ${ID}:${ID}

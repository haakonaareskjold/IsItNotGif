FROM php:8.0-fpm-alpine3.12

ARG USER=nemesis
ARG ID=1000

COPY ./ /var/www

 # Adding user
RUN adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)" \
    --no-create-home \
    --uid ${ID} \
    ${USER}

WORKDIR /var/www

USER ${ID}:${ID}

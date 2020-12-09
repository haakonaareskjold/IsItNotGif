FROM webdevops/php-nginx:8.0
WORKDIR /var/www/html
ARG USER=nemesis
ARG ID=1000



 # Adding user
RUN adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)" \
    --no-create-home \
    --uid ${ID} \
    ${USER}


USER ${ID}:${ID}

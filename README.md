# IsItNotGif

A simple web app that just checks your URL input if it's animated, \
it specifically checks for mp4 and gifs, embedded or not.

### How to run
1. `cp .env.example .env` in the terminal
2. use `docker-compose up -d` to create/run the containers
3. generate a laravel app key with `docker exec app php artisan key:generate`
4. check `http://localhost:8000` in your browser to use the app

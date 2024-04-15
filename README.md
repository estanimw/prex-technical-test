# Giphy Technical Test with PHP

Laravel project to interact with [Giphy API](https://developers.giphy.com/)

## Installation

1) Clone this repository and enter to its directory.
```bash
git clone git@github.com:estanimw/prex-technical-test.git
cd prex-technical-test
```
2) Create a `.env` file from `.env.example` file.
```bash
cp .env.example .env
```
3) Edit `.env` file filling the `GIPHY_API_KEY` variable with your API key following [this guide](https://developers.giphy.com/docs/api/#quick-start-guide).
4) Create the Docker image.
```bash
docker-compose build
```
5) Once the image is created, you'll be able to run the project using Docker. Get the container running with:
```bash
docker-compose up -d
```
6) Now you have to finish setting up the application: run the database migrations to create the DB tables and generate Passport's encryption keys. Also it's recommended that you run the DB seeder as it creates a user so you can start using the system right away:
```bash
docker exec -it prex_technical_test php artisan migrate
docker exec -it prex_technical_test php artisan db:seed
docker exec -it prex_technical_test php artisan passport:install
```
7) Now you should be able to try the system. The quickest way to test it trying to access [this route](http://localhost:80/) where you should get a response.

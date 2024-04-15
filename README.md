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

## Running tests locally
You may run the tests on your machine with this command:
```bash
docker exec -it prex_technical_test php artisan test
```

## Database connection
```
MariaDB
host: localhost
user: sail
password: password
database: laravel
port: 3306
```

## Documentation
### Use Case Diagram:
![Diagrama de Casos de Uso](https://github.com/estanimw/prex-technical-test/assets/63565054/a5a6f2f3-d194-4d28-9eea-828af6268f2c)

### Sequence Diagram:
![Diagrama de secuencia](https://github.com/estanimw/prex-technical-test/assets/63565054/8416066e-bcfe-473e-8210-ec60cdf52900)

### ERD
![ERD](https://github.com/estanimw/prex-technical-test/assets/63565054/a1fb6b8d-f395-43c0-87fd-18c2e93539a5)

**_NOTE:_** The Passport related entities were left out on purpose to focus only on the user and favorites entities

## Postman Endpoints Collection
Feel free to import [this collection](https://github.com/estanimw/prex-technical-test/blob/main/Prex%20Technical%20Test.postman_collection.json) to Postman or any other HTTP Client software to test the application.
If you seeded the DB with the command on the installation process, you may just login with the params of the login endpoint and the access token will be stored in the token collection variable to be used by the endpoints that require authentication.

## Considerations
- Every interaction with the system is logged using Laravel's built in logging functionalities. Once you start using your local version, you may find the logs inside the `storage/logs/` folder. On a production deploy these logs could be moved to another log management system that allows the processing and analysis of them.
- The controllers use custom Request objects that have the validation rules defined for each and this allows the controllers and services to abstract from the data validations as this objects reject the request if it's invalid before executing the controller method.

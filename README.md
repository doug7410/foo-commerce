## Foo Commerce
This is an example ecommerce app for viewing products, orders and inventory. 
It uses Laravel and Vue

### Requirements
- PHP 8.0
- Node 18

### Local Development with Docker
This project used Laravel Sail for local development.

- Run `composer install` using your local PHP 8.0

- Copy `.env.example` to `.env` 

- Run `./vendor/bin/sail up -d`

_Note: if you get an error about a port already in use update the appropriate port in `.env`. 
It will be either `FORWARD_DB_PORT` or `APP_PORT`. If you update `APP_PORT` make sure you change
it on `APP_URL=http://localhost:88` as well._

- Run `./vendor/bin/sail composer install`
- Run `./vendor/bin/sail npm install`
- Run `./vendor/bin/sail npm run dev`
- Run `./vendor/bin/sail artisan migrate`
- Run `./vendor/bin/sail artisan db:seed`

### Running Test
To run the test `./vendor/bin/sail test`

### View the site
You should now be able to view the app at http://localhost:88/login
If you changed the port just update it in that url.

You can log into the app with this email and password.
- **email**: larhonda.hovis@foo.com
- **pass**: cghmpbKXXK 

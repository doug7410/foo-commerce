## Foo Commerce

### Requirements

- PHP 8.1
- Node 16

### Local Development with Docker

1. Run `docker-compose up` . This will start the app container, a mysql container, and a testing 
mysql container
2. Run `docker ps` to get the container name. Example output 
    ``` 
    CONTAINER ID   IMAGE                               COMMAND                   CREATED         STATUS         PORTS                                               NAMES
    9e8eb7997e8d   sail-8.1/app                        "start-container"         5 minutes ago   Up 5 minutes   0.0.0.0:8181->80/tcp                                foo-commerce-foo-commerce-1
    ```
3. To enter the container, copy the container name and run `docker-exec -it <container-name> bash`
4. Install PHP and javascript dependencies and build javascript bundle - `composer install` `npm install` `npm run dev`
5. Migrate and seed the database `php artisan migrate` `php artisan db:seed`

### Running Test
To run the test enter the docker container and run `./vendor/bin/phpunit`

### View the site
After you start up the docker container the site will be running at [http://localhost:8181](http://localhost:8181) 
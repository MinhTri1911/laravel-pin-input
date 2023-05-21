## HOW TO RUN

Require docker & docker-compose

Running commands

````
docker-compose up -d
docker exec -it laravel-pin-input-server bash
php artisan serve --host 0.0.0.0
````
Then you can access to localhost:8000 on your browser

## HOW TO TEST

Running commands

````
docker-compose up -d
docker exec -it laravel-pin-input-server bash
php artisan test
````

## HOW TO USE

First of all you have to create an account and then it will send a new mail contain pin code.

Fill the pin code and then you can logg in

Same with login function, the home page only can access via user is logged in

## HOW TO CONFIGRUATION

you can config the setting in .env file

```
PIN_CODE_LENGTH=5
PIN_SECRET_MODE=1
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<userid>
MAIL_PASSWORD=<passcode>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

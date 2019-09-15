# NeuAlert

NeuAlert is a real-time web application monitoring message exchanges in a lot of channels of RabbitMQ.

## Prerequisities:
* **Laravel 5.8**
PHP >= 7.1.3
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension
Tokenizer PHP Extension
XML PHP Extension
Ctype PHP Extension
JSON PHP Extension
BCMath PHP Extension
* **PostgresSQL**
* **Node & NPM**
* **Redis server**

## Deployment
### Setting up dev server
1. Install `composer`
2. Install `node.js & npm`
3. Run this command in project root directory:
```
$ composer install
```
4. Generating `.env` file based on `.env.example`
5. Database migration
```
$ php artisan migrate
```
6. Run backend server
```
$ php artisan serve
```
7. Install laravel echo server for socket communication
```
$ npm install -g laravel-echo-server

```
8. Initialize socket server
```
$ laravel-echo-server init
```
9. Start Redis server
10. Start socket server
```
$ laravel-echo-server start
```
11. Navigate to `resources/frontend` and run this command:
```
$ npm install
```
12. Run frontend server
```
$ npm run start
```

### Setting up production server
It's same to set dev server for installing dependencies.
For backend;
- setting apache or nginx server.
- run socket server
```
$ npm install -g pm2
$ pm2 start socket-server.sh
```

For frontend;
run this command
```
$ npm run build
```

So frontend url should be `<site domain>/frontend`
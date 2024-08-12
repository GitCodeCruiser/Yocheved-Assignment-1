# Student & User Session Managment

A program to track student sessions to improve their practical or
theoretical knowledge gaps for any subject and generate an improvement report.
The tech stacks that can be used for this assignment are :
1. Laravel (Backend) = Version 10.10
2. Vue (Front-end) = Version 2.

1. Student CRUD interface.
2. Maintained student weekday availability.
3. User authentication
4. Schedule Sessions
5. Parse MS-Docx files
6. Generate reports using templates

> ### Student&UserSessionManagment Laravel codebase

This repo is functionality complete — PRs and issues welcome!

---

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation]'(https://laravel.com/docs/10.x')

Alternative installation is possible without local dependencies relying on [Docker](#docker).

Clone the repository

    git clone https://github.com/GitCodeCruiser/Student-UserSessionManagment.git

Switch to the repo folder

    cd Student&UserSessionManagment

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Install all the dependencies using composer

    composer install

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Generate a new application key

    php artisan key:generate

Start the local development server

    php artisan serve

You can now access the server at url given by above command, default is: http://localhost:8000

**TL;DR command list**

    git clone https://github.com/GitCodeCruiser/Student-UserSessionManagment.git
    cd Student&UserSessionManagment
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate

**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve

## Database seeding

**Populate the database with seed data with relationships which includes users, articles, comments, tags, favorites and follows. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Open the DBSeeder and set the property values as per your requirement

    database/seeds/DBSeeder.php

Run the database seeder and you're done

    php artisan db:seed

**_Note_** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh

## vue dependencies

Install all the dependencies using npm (**Make sure you have node and npm installed**)

    npm install

Make a build for vue

    npm run watch

## Docker (**optional**)

To install with [Docker](https://www.docker.com), run following commands:

```
git clone 'Git http url'
cd chrrrp
cp .env.example.docker .env
docker run -v $(pwd):/app composer install
cd ./docker
docker-compose up -d
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan db:seed
docker-compose exec php php artisan serve --host=0.0.0.0
```

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

# Code overview

## Folders

- `app/Models` - Contains all the Eloquent models
- `app/Http/Controllers/Apis` - Contains all the controllers related to apis
- `app/Http/Controllers` - Contains few basic controllers
- `app/Http/Middleware` - Contains all the middleware
- `app/Http/Request` - Contains max of validations
- `app/Http/Repositories` - Contains max of database related logic
- `app/Http/Resources` - Contains max of the responses
- `app/Http/Services` - Contains max of the logic of controllers
- `config` - Contains all the application configuration files
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file
- `resources/js` - Contains all the vuejs

## Environment variables

- `.env` - Environment variables can be set in this file

**_Note_** : You can quickly set the database information and other variables in this file and have the application fully working.

---

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api

# Testing Web

Run the node development server
npm run dev

The api can now be accessed at

    APP_URL: http://localhost:8000

If you find any issue then
empty storage/framework/sessions folder except .gitignore file

Request headers

| **Required** | **Key**          | **Value**        |
| ------------ | ---------------- | ---------------- |
| Yes          | Content-Type     | application/json |
| Yes          | X-Requested-With | XMLHttpRequest   |
| Optional     | Authorization    | Bearer {token}   |

---
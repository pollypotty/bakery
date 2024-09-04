# PROOFED - bakery application

The idea of creating this webshop came from a small family-owned artisan bakery.
The goal is to streamline ordering bakery products into one platform.
Usually the products of this bakery have different preparation timeframes (1,2-5 days).

With this webshop the customers can place orders for their desired food in advance and the bakery can have a brief
summary of what and when to prepare for the orders.
Additionally, this webshop can enhance coordinating the delivery of goods to different customers.

To enhance customer experience, the webshop includes features for online payments and allows users to sign in using
their Google accounts.

## Table of Contents

-[ ] Technological Overview
-[ ] Installation
- [ ] Usage
- [ ] Database
- [ ] Routes
- [ ] Features
- [ ] Flowchart

## Technological Overview

* Frontend technologies: HTML, CSS, Bootstrap, JavaScript, Vue3
* Frontend packages:
    * Flash messages: @smartweb/vue-flash-message
    * Stripe elements: @stripe/stripe-js
    * Pinia store: pinia
    * Local storage persistent state: pinia-plugin-persistedstate
    * Data validation: vee-validate, yup
* Backend - Laravel 10:
    * Extensions: Socialite, Stripe
* Database: MySQL

## Installation

### Prerequisites

Make sure you have the following installed:

-[ ] Docker
- [ ] Docker Compose

### Setting up the project

1. Clone the repository
    ```sh
   git clone https://github.com/pollypotty/bakery
   cd bakery
   ```
2. Start the development environment
    ```sh
    ./vendor/bin/sail up -d
    ```
3. Install dependencies
    ```sh
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install
    ```
4. Copy the example environment file and configure it
    ```sh
   cp .env.example .env
   ```
   Fill out:
    -[ ] database settings -> DB_DATABASE, DB_USERNAME, DB_PASSWORD
    - [ ] google api details -> GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET
    -[ ] stripe api details -> STRIPE_KEY, STRIPE_SECRET
5.
6. Generate application key
    ```sh
   ./vendor/bin/sail artisan key:generate
   ```
6. Run the database migration
    ```sh
   ./vendor/bin/sail artisan migrate
   ```

## Usage

### Running the application

Use the following command:

```sh
./vendor/bin/sail up
```

### Building for development

To compile your assets for development, run:

```sh
./vendor/bin/sail npm run dev
```

## Database

Database schema [DDL scripts](docs/database/ddl.sql) <br>
Database [relations visualization](docs/database/database_schema.png) <br>

## Routes

### Web Routes

|                  FEATURE                  |        ROUTE         | METHOD | PUBLIC | AUTHENTICATED USER | ADMIN |
|:-----------------------------------------:|:--------------------:|:------:|:------:|:------------------:|:-----:|
|              visit home page              |         '/'          |  get   |   X    |         X          |       |
|            visit products page            |     '/products'      |  get   |   X    |         X          |       |
|          visit registration page          |   '/registration'    |  get   |   X    |                    |       |
|             register new user             |   '/registration'    |  post  |   X    |                    |       |
|             visit login page              |       /'login'       |  get   |   X    |                    |       |
|          log in the application           |       '/login'       |  post  |   X    |                    |       |
| redirection to google provided login page | '/auth/google/login' |  get   |   X    |                    |       |
|        log out of the application         |      '/logout'       |  post  |        |         X          |       |
|            go to profile page             |      '/profile'      |  get   |        |         X          |       |
|             go to order page              |       '/order'       |  get   |        |         X          |       |
|              go to cart page              |       '/cart'        |  get   |        |         X          |       |
|          go to admin login page           |    '/admin/login'    |  get   |   X    |                    |       |
|              log in as admin              |    '/admin/login'    |  post  |   X    |                    |       |
|        go to admin dashboard page         |  '/admin/dashboard'  |  get   |        |                    |   X   |
|               admin log out               |   '/admin/logout'    |  post  |        |                    |   X   |
|         go to admin products page         |  '/admin/products'   |  get   |        |                    |   X   |

### API Routes

|                       FEATURE                       |          ROUTE           | METHOD | PUBLIC | AUTHENTICATED USER | ADMIN |
|:---------------------------------------------------:|:------------------------:|:------:|:------:|:------------------:|:-----:|
| fetch the saved addresses of the authenticated user |    '/user_addresses'     |  get   |        |         X          |       |
|                  save new address                   |    '/user_addresses'     |  post  |        |         X          |       |
|                    create order                     |         '/order'         |  post  |        |         X          |       |
|               initiate online payment               | '/create-payment-intent' |  post  |        |         X          |       |
|                    edit product                     |  '/products/{product}'   | patch  |        |                    |   X   |
|                   delete product                    |  '/products/{product}'   | delete |        |                    |   X   |
|                   create product                    |       '/products'        |  post  |        |                    |   X   |        

## Flowchart

Located in [detailed documentation](/docs/flowcharts)

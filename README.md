# Cart Readme

**Prerequisites**

Before you start, ensure you have the following installed:

*   PHP 8.1 or higher
*   Composer
*   MySQL or another supported database
*   Git

**Installation**

Clone the Repository:

git clone <repository-url>

cd <project-folder>

  

**Install PHP Dependencies:**

Run the following command to install Laravel's required PHP packages:

composer install

Create and Configure .env File:

Duplicate the .env.example file and rename it to .env:

cp .env.example .env

Open the .env file and set up your application configuration, including database credentials, application URL, and other settings.

  

**Generate Application Key:**

Generate the application key for security:

php artisan key:generate

  

**Set Up the Database:**

Create a new database for the application and seed initial data.

Update the database credentials in the .env file:

DB\_CONNECTION=mysql

DB\_HOST=127.0.0.1

DB\_PORT=3306

DB\_DATABASE=<database\_name>

DB\_USERNAME=<username>

DB\_PASSWORD=<password>

  

Run Migrations

Execute the following command database schema

php artisan migrate

Execute the following command to seed db data
php artisan db:seed

It creates two user
1. admin - admin@cart.com, password:"password"
2. customer - customer@cart.com password:"password"

**interacting with the api**
1. Use a tool like postman to access endpoiints
  a. /api/register - to register a new user
  b. /api/login - to login a user
  c. /api/products - to manipulate product
**Start redis-server**
sudo service redis-server start

**Running the Application**

Start the Local Development Server:

php artisan serve

The application will be available at [http://localhost:8000](http://localhost:8000).

## Pre Requisites
1. Xampp/Lamp installed
2. Make sure latest version of PHP eg.  8/8.1 is installed 
3. Make sure MySql is installed
4. Code Editor eg. Visual Studio Code/NotePad++
5. Composer should be installed

## Installation
After cloning this repo. Open the repo into Code Editor eg. Visual Studio Code or open project root directory in command prompt or shell and please run this command to install dependencies.

   ```bash
   composer install
   ```
Create a new file in root directory with name .env and copy contents from .env.example   
Run the following command to set application key if it is not set already
   ```bash
   php artisan key:generate
   ```

Create a database in mysql eg. "aspire_db". (Sample database added)
Modify .env and add the database name with correct database connection credentials
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=aspire_db
    DB_USERNAME="db_username"
    DB_PASSWORD="db_password"
   ```

Run the following command to setup the database
   ```bash
   php artisan migrate:refresh --seed
   ```

Run following command to start the application
   ```bash
   php artisan serve
   ```
The application will be started at http://127.0.0.1:8000 

## Usage
Once Application started you can test the api created for loan scheduled Payments. The POSTMAN collections for REST APIs are already added into the project root directory

Credentials to test User Actions
   ```bash
   email:test@example.com
   password:123456
   ```

Credentials to test Admin Actions
   ```bash
   email:test@admin.com
   password:123456
   ```

Please get the access token for each user by hitting the login API.  Pass this into header from POSTMAN through the Bearer Token to use subsequent request. (Please refer to the ASPIRE_API.docx for usage).

## Note
Important Commands
   ```bash
     composer dump-autoload
     php artisan optimize:clear
     php artisan route:list
   ```

Cheers(::)

## Installation
*. After cloning this repo please run this to install dependecies. Open the repo into Code Editor eg. Visual Studio Code or Open Project root directory in command prompt or shell 

   ```bash
   composer install
   ```
*. Run the following command to set application key if it is not set already
   ```bash
   php artisan key:generate
   ```


*. Create a database in mysql eg. "aspire_db".
*. Create a new file in root directory with name .env and copy contents from .env.example   
*. Modify .env and add the database name with correct database connection credentials
   ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=aspire_db
    DB_USERNAME="db_username"
    DB_PASSWORD="db_password"
   ```

*. Run the following command to setup the database
   ```bash
   php artisan migrate:refresh --seed
   ```

*. Run following command to start the application
   ```bash
   php artisan serve
   ```
The application will be started at http://127.0.0.1:8000 

Once Application started you can test the api created for loan scheduled Payments. The POSTMAN collection for REST APIs are already added into the project root directory

*. Credentials to test User Actions
   ```bash
   email:test@example.com
   password:123456
   ```

*. Credentials to test Admin Actions
   ```bash
   email:test@admin.com
   password:123456
   ```

Please get the access token for each user by hitting the login API.  Pass this into header from POSTMAN through the Bearer Token to use subsequent request. 

Cheers(::)
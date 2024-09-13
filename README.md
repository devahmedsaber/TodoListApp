# Laravel Todo List Application
This is a simple Laravel Todo list application with useful features, built using MySQL, JWT authentication, repository and service patterns and more.

## Features
- **JWT Authentication** for secure API access.
- **Todo Management** with image attachments.
- **Email Notifications** using Mailtrap for development.
- **Pagination, Search** functionality for Todos.
- **Unit Testing** for core features (Index, Show, Store, Update, Delete, Search, Pagination, Email Notification).
- **API Documentation** available to (Postman).
- **Repositories and Services** for writing clean code.
- **Laravel Requests** custom request for APIs.
- **Events and Listeners** for email notifications.
- **Seeders** for importing random data like (1000) todos.
- **Exceptions** custom exceptions for handling errors.
- **Resources** For handling API responses.
- **API Response Trait** for handling success, error and validation responses for APIs.
- **JSON Langs Files** for translating API responses.
- **Expected Response Classes** for handling unit testing files.

## Prerequisites
Before running the project, ensure you have the following installed:
- PHP 8.x
- MySQL
- Composer
- Mailtrap Account (for email testing)
- Postman
- VS Code Or PHPStorm

## Getting Started & Installation Steps
1. Clone the repository:
  - git clone https://github.com/devahmedsaber/TodoListApp.git
  - cd TodoListApp
2. Install dependencies:
  - composer install
3. Set up environment variables:
  - Copy `.env.example` to `.env` (cp .env.example .env)
  - Update database configuration:
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password
  - Update mailtrap configuration (for email notifications):
        MAIL_MAILER=smtp
        MAIL_HOST=smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=your_mailtrap_username
        MAIL_PASSWORD=your_mailtrap_password
        MAIL_ENCRYPTION=null
4. Generate the application key:
  - php artisan key:generate
5. Generate the JWT secret (for authentication):
  - php artisan jwt:secret
6. Run the database migrations:
  - php artisan migrate
7. Run seeders (for seed data like 1000 todos):
  - php artisan db:seed
8. Start the development server:
  - php artisan serve
9. Access the application:
  - Open your browser and navigate to `http://localhost:8000`.
9. Running unit tests:
  - If you want to run all tests run this command (php artisan test).
  - If you want to run specific unit test run this command like (php artisan test --filter IndexTodoTest).

## Some Guidelines
1. API Documentation
  You can view the API documentation (Postman) by visiting the following link:
  `https://documenter.getpostman.com/view/27286122/2sAXqng5fV`
  Or you can add the todo list app collection to your postman collections via this link:
  `https://api.postman.com/collections/27286122-c797b74c-c8b6-4cbb-99eb-3eda426a3107?access_key=PMAT-01J7PM4KBJ9PEGMFC50HV6XGM1`
  Or you can add the todo list app collection to your postman collections by importing it manually.
  The todo list app collection exists in root directory with project files called (TodoListApp.postman_collection).
2. Email Notifications
  The app uses Mailtrap to simulate sending email notifications. Ensure your Mailtrap credentials are correctly configured in the .env file. Emails will be logged in your Mailtrap account.
3. JWT Authentication
  All API requests are secured with JWT authentication. Make sure to authenticate by obtaining a token via the /api/auth/login endpoint and including it in your requests.

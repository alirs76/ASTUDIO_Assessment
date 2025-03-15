# Project Management System with EAV Support

A Laravel-based project management system with dynamic attributes (EAV) support, user authentication, and timesheet
tracking.

## Features

- User Authentication (Register, Login, Logout)
- Project Management with Dynamic Attributes
- Timesheet Tracking
- RESTful API
- Flexible Filtering System

## Requirements

- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Laravel Passport

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy `.env.example` to `.env` and configure your database:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations and Seeders:
   ```bash
   php artisan migrate --seed
   ```
6. Generate a Passport keys:
   ```bash
   php artisan passport:keys
   ```
7. Create a Passport Personal Client:
   ```bash
   php artisan passport:client --personal --name="Personal Access Client"
   ```

## API Documentation

### Authentication

#### Register

- **POST** `/api/register`

```json
{
   "name": "John Doe",
   "email": "john@example.com",
   "password": "password123",
   "password_confirmation": "password123"
}
```

#### Login

- **POST** `/api/login`

```json
{
   "email": "john@example.com",
   "password": "password123"
}
```

### Projects

#### List Projects

- show one **GET** `/api/projects/{project id}`
- list **GET** `/api/projects`
- Supports pagination: `?page=1&perPage=15`
- Supports filtering: `?filter[name]=Project1&filter[status]=active`
- Supports **Attribute (EAV)** filtering:`?filter[attribute][start_date]=>=2024-02-03&filter[attribute][department]=LIKEProject1`
   - Operands: `<=,<,=,>,>=,!=,LIKE` operand must stick before value except for `=` that don't need to mention and is
     default.

#### Project

- create **POST** `/api/projects`
- update **PUT** `/api/projects/{project id}`

```json
{
   "name": "New Project",
   "status": "active",
   "attributes": {
      "client": "ACME Corp",
      "priority": "high"
   }
}
```
- delete **DELETE** `api/project/{project id}`

### Timesheets

#### List Timesheets

- **GET** `/api/timesheets`
- Supports pagination: `?page=1&perPage=15`

#### Create Timesheet

- **POST** `/api/timesheets`

```json
{
   "project_id": 1,
   "description": "Working on feature X",
   "hours": 8,
   "date": "2025-03-14"
}
```

### Attributes

#### List Attributes

- **GET** `/api/attributes`

#### Create Attribute

- **POST** `/api/attributes`

```json
{
   "name": "priority",
   "type": "string",
   "options": [
      "low",
      "medium",
      "high"
   ]
}
```

## Test Credentials

Create these credentials with register API for testing the APIs:

```
Email: test@example.com
Password: password123
```

## Response Format

All API responses follow this structure:

### Success Response

```json
{
   "data": [
      ...
   ],
   "currentPage": 1,
   "perPage": 15,
   "total": 50,
   "lastPage": 4
}
```

### Error Response

```json
{
   "message": "Error message",
   "errors": {
      "field": [
         "Validation error message"
      ]
   }
}
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

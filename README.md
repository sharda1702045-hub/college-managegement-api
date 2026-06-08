# College Management API

A secure, RESTful API built with PHP 8.2+ and Laravel 11.x to manage students, courses, and course enrollments. 

This project demonstrates clean coding standards, database design, token-based authentication (Laravel Sanctum), Role-Based Access Control (Spatie), Service-Repository architecture, automatic caching, robust input validation, and global error handling.

---

## Technical Stack & Architectural Decisions

* **Framework**: Laravel 11.x
* **Language**: PHP 8.2+
* **Database**: MySQL 8.0+
* **Authentication**: Token-based via **Laravel Sanctum**
* **RBAC (Role-Based Access Control)**: Managed using **Spatie Laravel Permission**. Two roles are defined:
  - `admin`: Full CRUD permissions (`view-*` and `manage-*`).
  - `staff`: Read-only permissions (`view-*` GET endpoints only).
* **Design Patterns**: 
  - **Repository Pattern**: Abstracted database access layers.
  - **Service Layer Architecture**: Decoupled controllers from core business logic (caching, checking duplication, data manipulation).
* **Caching**: Version-based caching applied to lists (Students, Courses, Enrollments). Automatic cache invalidation triggers on create, update, or delete operations.
* **Rate Limiting**: Enforced via custom API throttlers limiting requests to protect system availability.
* **Database Optimization**: Custom schema length configurations avoiding MySQL UTF8MB4 index limit constraints.

---

## Project Structure

The project has a clear separation of concerns:

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/                  # API Controllers (Auth, Student, Course, Enrollment)
│   ├── Requests/                 # Input FormRequest Validation Classes
│   └── Middleware/               # Core middlewares (including Spatie permissions)
├── Models/                       # Eloquent Models (User, Student, Course, Enrollment)
├── Repositories/                 # Data access layer (Interfaces and implementations)
└── Services/                     # Business logic layer (Caching, calculations, mappings)
routes/
└── api.php                       # Route definitions mapped with Sanctum and Spatie middlewares
```

---

## Installation & Setup

### 1. Prerequisites
Ensure you have the following installed on your system:
* PHP 8.2 or higher
* Composer
* MySQL 8.0 or higher
* Git

### 2. Clone the Repository
```bash
git clone <repository_url>
cd college-management-api
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Environment Configuration
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```
Open the `.env` file and configure your database settings:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=college-management-api
DB_USERNAME=root
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Run Database Migrations & Seeds
Run migrations to set up tables and execute seeds to preconfigure Spatie roles, permissions, and test accounts:
```bash
php artisan migrate --seed
```

This will automatically create:
* **Admin User**:
  - Email: `admin@example.com`
  - Password: `password`
  - Role: `admin` (Full CRUD operations allowed)
* **Staff User**:
  - Email: `staff@example.com`
  - Password: `password`
  - Role: `staff` (Read-only GET operations allowed)

---

## Running the Project

### Start Development Server
```bash
php artisan serve
```
By default, the server runs at `http://127.0.0.1:8000`.

### Run Automated Tests
Execute the feature test suite (running on an isolated in-memory SQLite database):
```bash
php artisan test
```

---

## API Documentation

### Endpoints Overview

| Method | Endpoint | Auth | RBAC Role | Description |
|---|---|---|---|---|
| `POST` | `/api/register` | Public | None | Register a new user (gets `staff` role by default) |
| `POST` | `/api/login` | Public | None | Login user & return plain-text Sanctum token |
| `POST` | `/api/logout` | Protected | `admin` / `staff` | Revoke current user session token |
| `GET` | `/api/students` | Protected | `admin` / `staff` | Get list of students (Supports search and pagination) |
| `POST` | `/api/students` | Protected | `admin` | Create a new student record |
| `GET` | `/api/students/{id}` | Protected | `admin` / `staff` | Get student details by ID |
| `PUT` | `/api/students/{id}` | Protected | `admin` | Update student details by ID |
| `DELETE` | `/api/students/{id}`| Protected | `admin` | Delete student by ID |
| `GET` | `/api/courses` | Protected | `admin` / `staff` | Get list of courses |
| `POST` | `/api/courses` | Protected | `admin` | Create a new course |
| `GET` | `/api/courses/{id}` | Protected | `admin` / `staff` | Get course details by ID |
| `PUT` | `/api/courses/{id}` | Protected | `admin` | Update course details by ID |
| `DELETE` | `/api/courses/{id}` | Protected | `admin` | Delete course by ID |
| `GET` | `/api/enrollments` | Protected | `admin` / `staff` | Get list of student course enrollments |
| `POST` | `/api/enrollments` | Protected | `admin` | Enroll a student in a course (prevents duplicates) |
| `DELETE` | `/api/enrollments/{id}`| Protected | `admin` | Remove an enrollment by ID |

---

## Validation & Error Responses

### Success Response Format
All successful operations return a standard envelope structure:
```json
{
  "success": true,
  "message": "Student created successfully",
  "data": {
    "id": 1,
    "first_name": "Bob",
    "last_name": "Jones",
    "email": "bob@example.com",
    "phone": "9876543210",
    "date_of_birth": "1999-05-15"
  }
}
```

### Validation Error Response (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "phone": ["The phone number format is invalid. It must be a valid phone format with 10 to 20 digits."]
  }
}
```

### Resource Not Found (404)
```json
{
  "success": false,
  "message": "Resource not found",
  "errors": {}
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthorized request",
  "errors": {}
}
```

### Access Denied (403)
```json
{
  "success": false,
  "message": "Access denied: unauthorized role or permission",
  "errors": {}
}
```

---

## Deliverables in Repository

* **Database Schema DDL**: [database_schema.sql](file:///c:/wamp64/www/college-management-api/database_schema.sql)
* **OpenAPI / Swagger Specs**: [swagger.json](file:///c:/wamp64/www/college-management-api/public/swagger.json) (interactive UI accessible at `http://127.0.0.1:8000/api/documentation`)
* **Postman Collection**: [Postman_Collection.json](file:///c:/wamp64/www/college-management-api/Postman_Collection.json)

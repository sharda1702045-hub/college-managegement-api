# College Management API

A secure, RESTful API built with PHP 8.2+ and Laravel 11.x to manage students, courses, and course enrollments. 

---

## 🔐 Core System Rules

This is an **Admin-Only API System**:
* **Admin Login Only**: Only users with the `Admin`, `Super Admin`, or legacy lowercase `admin` role are permitted to log in via the API and retrieve tokens.
* **Staff Blocked**: Staff users and standard users are blocked from logging in on the API.
* **No Registration Endpoint**: The `/api/register` endpoint has been completely disabled and removed from the application routes and Swagger UI documentation.
* **Admin UI Creation**: Users, staff, and admin accounts must only be created by an administrator via the Admin Panel interface.

---

## 1. 📥 Project Clone & Setup

### Clone the Repository
```bash
git clone https://github.com/sharda1702045-hub/college-managegement-api.git
cd college-managegement-api
git checkout main
```

### Setup Environment & Dependencies
1. **Install Composer dependencies**:
   ```bash
   composer install
   ```
2. **Setup environment config**:
   ```bash
   cp .env.example .env
   ```
3. **Generate application key**:
   ```bash
   php artisan key:generate
   ```
4. **Configure your Database**:
   Create a database (e.g. `college-management-api`) and update the database variables in the `.env` file:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=college-management-api
   DB_USERNAME=root
   DB_PASSWORD=YOUR_PASSWORD
   ```
5. **Run Migrations & Seeds**:
   ```bash
   php artisan migrate --seed
   ```
6. **Start local development server**:
   ```bash
   php artisan serve
   ```

---

## 2. 🧑💻 Admin Setup

Administrators are either seeded automatically or can be created via Tinker.

### Auto-Seeded Admin Credentials
* **Super Admin**:
  * Email: `superadmin@example.com`
  * Password: `password`
* **Admin User**:
  * Email: `admin@example.com`
  * Password: `password`

### Creating an Admin User via Tinker
```bash
php artisan tinker
```
```php
$user = App\Models\User::create([
    'name' => 'New Admin',
    'email' => 'newadmin@example.com',
    'password' => Hash::make('securepassword123'),
]);

$user->assignRole('Admin');
```

---

## 3. 🚀 Swagger Documentation

* **Interactive Swagger UI**: Accessible at `http://127.0.0.1:8000/api/documentation` (or your active server IP)
* **Swagger Specs Locations**: 
  - Root configuration: `swagger.json`
  - Public directory: `public/swagger.json` (served to the browser UI client)

---

## 4. 🔐 Authentication System

### Login Endpoint
`POST /api/login`

* **Payload Requirements**:
  ```json
  {
    "email": "admin@example.com",
    "password": "password"
  }
  ```
* **Authentication Rules**:
  * Only users possessing administrative roles (`Super Admin`, `Admin`, or `admin`) can authenticate.
  * Access token is returned on success.
  * Attempts by staff users or accounts without admin roles are rejected with a validation error (`422 Unprocessable Content`).

### Removed Endpoint
`POST /api/register`
* This route is completely removed from `routes/api.php` and does not appear in the Swagger specs.

---

## 5. 🧭 API Routes Structure

All endpoints under the `/api` prefix require authentication via Laravel Sanctum bearer token (except login) and are restricted by role permission gates.

### Auth APIs
* `POST /api/login` - Authenticate admin credentials and generate access token.
* `POST /api/logout` - Revoke current authenticated session token (Sanctum protected).

### Student APIs (Admin Protected)
* `GET /api/students` - Paginated and searchable list of students.
* `POST /api/students` - Register a new student record.
* `GET /api/students/{id}` - Fetch detailed student profile by ID.
* `PUT /api/students/{id}` - Update student information by ID.
* `DELETE /api/students/{id}` - Delete student record by ID.

### Course APIs (Admin Protected)
* `GET /api/courses` - List all courses.
* `POST /api/courses` - Create a new course record.
* `GET /api/courses/{id}` - Fetch course details by ID.
* `PUT /api/courses/{id}` - Update course information by ID.
* `DELETE /api/courses/{id}` - Delete course record by ID.

### Enrollment APIs (Admin Protected)
* `GET /api/enrollments` - List all student-course enrollments.
* `POST /api/enrollments` - Enroll a student in a course.
* `DELETE /api/enrollments/{id}` - Remove an enrollment record by ID.

---

## 🛡️ Security Rules & Role-Based Access Control

* **Admin Verification**: Configured in `AuthService` to validate administrative roles before issuing Sanctum tokens.
* **Exception Responses**: Non-admin login attempts yield standard validation error structure:
  ```json
  {
    "success": false,
    "message": "Validation failed",
    "errors": {
      "email": [
        "Access denied: only administrators are allowed to access the API."
      ]
    }
  }
  ```

---

## 📄 Swagger Cleanup
* The `/api/register` path is excluded from `swagger.json` and `public/swagger.json`.
* Only functional admin endpoints are listed in the Swagger UI.

---

## 🧪 Testing Instructions

Run the testing suite using:
```bash
php vendor/bin/phpunit
```

### Verified Test Scenarios
* **Admin Login Success**: Asserts that an administrator logs in and gets a Sanctum token.
* **Staff Login Failure**: Asserts that staff credentials fail to authenticate with a `422` validation response.
* **Registration Disabled Check**: Asserts that `/api/register` yields a `404 Not Found` response.

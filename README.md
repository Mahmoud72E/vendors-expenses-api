# Vendors & Expenses API

A **RESTful API** built with **Laravel 10/11** to manage vendors, expense categories, and expenses.
Supports **role-based authentication**, **permissions**, and **expense summary reports**.

---

## Table of Contents

* [Features](#features)
* [Technologies](#technologies)
* [Requirements](#requirements)
* [Installation](#installation)
* [Authentication](#authentication)
* [API Endpoints](#api-endpoints)
* [Validation Rules](#validation-rules)
* [Testing](#testing)
* [Bonus Features](#bonus-features)

---

## Features

* **Authentication**: Sanctum token-based
* **Roles**: `admin` (full access), `staff` (create/read expenses only)
* **Vendors CRUD**: List, create, update, delete (admin only)
* **Expense Categories CRUD**: List, create, update, delete (admin only, deletion blocked if linked expenses exist)
* **Expenses CRUD**: List (with filters), create, view, delete (staff only for their own, admin for any)
* **Summary Report**: Total expenses grouped by month and category
* **Validation & Errors**: Proper HTTP codes (422, 403, 401, etc.)

---

## Technologies

* PHP 8.2+
* Laravel 12
* MySQL
* Composer
* Sanctum for authentication

---

## Requirements

* PHP 8.2+
* MySQL 8+
* Composer
* Laravel CLI

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/Mahmoud72E/vendors-expenses-api.git
cd vendors-expenses-api
```

2. Install dependencies:

```bash
composer install
```

3. Copy `.env.example`:

```bash
cp .env.example .env
```

4. Set database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Generate application key:

```bash
php artisan key:generate
```

7. Run the server:

```bash
php artisan serve
```

---

## Authentication

* Login endpoint: `POST /api/login`

**Request:**

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

**Response:**

```json
{
  "token": "sanctum-token",
  "user": {
    "id": 1,
    "name": "Admin User",
    "role": "admin"
  }
}
```

* Use token for protected routes:

```http
Authorization: Bearer {token}
```

* Logout endpoint: `POST /api/logout`

---

## API Endpoints

### Vendors (admin only)

| Method | Endpoint          | Description      |
| ------ | ----------------- | ---------------- |
| GET    | /api/vendors      | List all vendors |
| POST   | /api/vendors      | Create vendor    |
| GET    | /api/vendors/{id} | View vendor      |
| PUT    | /api/vendors/{id} | Update vendor    |
| DELETE | /api/vendors/{id} | Delete vendor    |

### Categories (admin only)

| Method | Endpoint             | Description                                  |
| ------ | -------------------- | -------------------------------------------- |
| GET    | /api/categories      | List categories                              |
| POST   | /api/categories      | Create category                              |
| GET    | /api/categories/{id} | View category                                |
| PUT    | /api/categories/{id} | Update category                              |
| DELETE | /api/categories/{id} | Delete category (blocked if linked expenses) |

### Expenses

| Method | Endpoint           | Description                                     |
| ------ | ------------------ | ----------------------------------------------- |
| GET    | /api/expenses      | List expenses (filters: date, vendor, category) |
| POST   | /api/expenses      | Create expense                                  |
| GET    | /api/expenses/{id} | View expense                                    |
| PUT    | /api/expenses/{id} | Update expense                                  |
| DELETE | /api/expenses/{id} | Delete expense (staff only own)                 |

### Summary Report

| Method | Endpoint              | Description                        |
| ------ | --------------------- | ---------------------------------- |
| GET    | /api/expenses-summary | Total expenses by month & category |

---

## Validation Rules

* **Expenses**:

  * `amount` > 0
  * `category_id` must be active
  * `date` required
* **Categories**:

  * `name` required
* **Vendors**:

  * `name` required

---

## Testing

Run **feature tests** using PHPUnit:

```bash
php artisan test
```

Tests cover:

* Authentication
* Role permissions
* CRUD operations
* Category deletion rules
* Summary report

---

## Bonus Features (Optional)

* File upload for expense attachments
* CSV export for expenses and summary reports
* Postman collection included

---

## Author

**Mahmoud Mohamed** – Backend Developer (Laravel, PHP)
[GitHub](https://github.com/Mahmoud72E)


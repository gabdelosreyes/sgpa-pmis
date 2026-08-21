# Signal Regiment, Philippine Army - Personnel Management Information System (SRPA-PMIS)

This repository contains the source code for the Personnel Management Information System of the Signal Regiment, Philippine Army. It is designed to be a secure, military-standard application for managing army personnel, user roles, and granular permissions with full audit logging.

## Features

- **Dashboard**: High-level overview of total users, active personnel, and basic demographic statistics.
- **Personnel Management**: Add, edit, view, and deactivate personnel records. Features robust search and filtering capabilities using data tables.
- **User Management**: Manage system users and their active statuses securely.
- **Roles & Permissions**: Granular, role-based access control (RBAC). Allows dynamic creation of roles with specific capabilities (e.g., View Personnel, Edit Users, Add Roles).
- **Activity Logs**: Automatic auditing of system actions. Tracks who did what, when, and exactly which record was modified.
- **Military-Standard UI**: A strictly formal, high-contrast, clutter-free user interface designed for efficiency.

## Technology Stack

*   **Laravel 13.x**: PHP Web Framework
*   **TALL Stack**:
    *   **T**ailwind CSS (v4)
    *   **A**lpine.js
    *   **L**aravel
    *   **L**ivewire (v3)
*   **Livewire PowerGrid**: Advanced dynamic data tables
*   **TallStackUI**: Component library for seamless modal and toast integrations
*   **Spatie Packages**: 
    *   `laravel-permission` (Granular RBAC)
    *   `laravel-activitylog` (Audit trails)
*   **Database**: PostgreSQL
*   **Testing**: PHPUnit

## Default Admin Account

Upon running the database seeders, a default Super Admin account is generated with unrestricted access to all modules:

- **Email**: `admin@sgpa.mil.ph`
- **Password**: `password`

> **Note:** Ensure you change this password immediately in a production environment.

## Setup Instructions

1. Clone the repository and navigate into the directory.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install front-end dependencies and build assets:
   ```bash
   npm install
   npm run build
   ```
4. Copy the environment file and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configure your PostgreSQL database in the `.env` file (the default DB name is `sgpa_pmis`).
6. Run the database migrations and seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```
7. Serve the application:
   ```bash
   php artisan serve
   ```

## Testing

The system includes automated feature tests for mission-critical paths (Authentication, Role Enforcement, and Personnel Deactivation). To run the test suite:

```bash
php artisan test
```

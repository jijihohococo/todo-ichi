# Todo Ichi

A simple task management application built with PHP, Vue 3, and MySQL.

## Features

- Create todos.
- View all todos.
- Edit existing todos.
- Delete todos.
- Mark todos as completed or active.
- Search and filter todos.
- View todo statistics.

## Technology Stack

### Backend
- PHP 7.3+
- MySQL
- Composer
- IchiRoute
- IchiORM
- IchiValidation

### Frontend
- Vue 3
- Vue Router
- Vite
- JavaScript
- CSS

## Requirements

Before setting up the project, make sure you have installed:

| Software | Requirement |
|---|---|
| PHP | 7.3 or later |
| Composer | Latest stable version |
| MySQL | MySQL server |
| Node.js | LTS version recommended |
| npm | Installed with Node.js |
| Git | Latest stable version |

You can verify your installations with:

```bash
php -v
composer --version
mysql --version
node -v
npm -v
git --version
```

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/jijihohococo/todo-ichi.git
```

Enter the project directory:

```bash
cd todo-ichi
```

### 2. Install PHP dependencies

Run Composer:

```bash
composer install
```

### 3. Install frontend dependencies

Run:

```bash
npm install
```

### 4. Create the database

Create a MySQL database for the application.

For example:

```sql
CREATE DATABASE todo_ichi
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Select the database:

```sql
USE todo_ichi;
```

Create the `todos` table:

```sql
CREATE TABLE `todos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
```

### 5. Configure environment variables

Copy `.env.example` to `.env`.

**Linux / macOS / WSL:**

```bash
cp .env.example .env
```

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

Open `.env` and configure your database connection:

```env
APP_ENV=development

VITE_PORT=5173
SERVER_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_ichi
DB_USERNAME=root
DB_PASSWORD=
```

Update the database values according to your MySQL configuration.

> **Note:** The current database configuration in `app/Kernel.php` does not pass `DB_PORT` to the database connector. The default MySQL port (3306) is therefore assumed unless the connection configuration is updated.

Never commit your actual `.env` file or database credentials.

## Running the Application

The application uses the `APP_ENV` environment variable to determine how frontend assets are loaded.

### Development Mode

Set the following in `.env`:

```env
APP_ENV=development
```

You need two terminal sessions.

**Terminal 1: Start the Vite development server**

```bash
npm run dev
```

**Terminal 2: Start the PHP application**

From the project root:

```bash
php -S localhost:8000 -t public
```

Open the application:

```text
http://localhost:8000
```

In development mode, Vue is loaded directly from the Vite development server. You do not need to run `npm run build`.

### Production Mode

Set the following in `.env`:

```env
APP_ENV=production
```

**1. Build the frontend assets**

From the project root, run:

```bash
npm run build
```

The compiled assets are generated in:

```text
public/vue/
```

The build produces:

- `public/vue/app.js`
- `public/vue/main.css`

**2. Start the PHP application**

Configure your PHP web server to serve the `public` directory as its document root.

For a local test using PHP's built-in server:

```bash
php -S localhost:8000 -t public
```

**3. Open the application**

```text
http://localhost:8000
```

In production mode, the application loads the compiled JavaScript and CSS files from `public/vue/` instead of connecting to the Vite development server.

### Development vs. Production

| Configuration | Development | Production |
|---|---|---|
| `APP_ENV` | `development` | `production` |
| Frontend source | Vite development server | Compiled assets |
| `npm run dev` | Required | Not required |
| `npm run build` | Not required | Required |
| Asset location | Vite server | `public/vue/` |

**Important:** Set `APP_ENV` before starting the PHP application. When switching to production, make sure you have run `npm run build` so the compiled assets exist.

Build the frontend assets:

```bash
npm run build
```

The production assets are generated in:

```text
public/vue/
```

The build produces:

- `public/vue/app.js`
- `public/vue/main.css`

Start the PHP application using your configured PHP web server and open its URL in your browser.

## Available API Endpoints

The application exposes the following REST API endpoints:

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/todos` | Retrieve all todos |
| GET | `/api/todos/{id}` | Retrieve a todo |
| POST | `/api/todos` | Create a todo |
| PUT | `/api/todos/{id}` | Update a todo |
| DELETE | `/api/todos/{id}` | Delete a todo |

### Example: Create a Todo

**Request**

```http
POST /api/todos
Content-Type: application/json
```

```json
{
  "title": "Learn Vue 3",
  "description": "Practice Vue Composition API"
}
```

The backend initializes `is_completed` to `false` when creating a todo.

## Project Structure

```text
todo-ichi/
├── app/
│   ├── Controllers/
│   │   ├── API/
│   │   │   └── TodoController.php
│   │   └── PageController.php
│   ├── Models/
│   │   └── Todo.php
│   ├── Repositories/
│   │   ├── TodoRepository.php
│   │   └── TodoRepositoryInterface.php
│   └── Kernel.php
├── resources/
│   └── views/
│       └── index.php
├── routes/
│   └── web.php
├── src/
│   └── vue/
│       ├── components/
│       ├── router/
│       ├── services/
│       ├── styles/
│       ├── utils/
│       ├── views/
│       ├── App.vue
│       └── main.js
├── public/
│   └── vue/
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

## Development Commands

| Command | Description |
|---|---|
| `composer install` | Install PHP dependencies |
| `npm install` | Install frontend dependencies |
| `npm run dev` | Start Vite development server |
| `npm run build` | Build production frontend assets |
| `php -S localhost:8000` | Start PHP development server |

## License

See the repository for license information.
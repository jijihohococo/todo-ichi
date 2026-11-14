# AGENTS.md

## 1. Project Overview

This repository is `jijihohococo/todo-ichi`.

It is a PHP MVC application built using the Ichi PHP framework and its associated packages.

The application is intended to become a TODO management application.

**Current implementation status:**
- PHP MVC application bootstrap is in place.
- Database connection is configured through environment variables.
- Web and API routes are registered.
- A `Todo` model exists.
- Read-only TODO API endpoints exist.
- The default homepage is still a framework welcome page.
- Full TODO management functionality has not yet been implemented.

Do not assume that functionality exists just because it is a typical feature of a TODO application.

## 2. Technology Stack

Use the existing technology stack:

- PHP (maintain PHP 7.3 compatibility unless the project explicitly changes its minimum version).
- Composer for dependency management.
- Ichi PHP framework packages:
  - `jijihohococo/ichi-route`
  - `jijihohococo/ichi-template`
  - `jijihohococo/ichi-orm`
  - `jijihohococo/php-env`
  - `jijihohococo/ichi-validation`
- MySQL as the currently configured database connection.
- Apache `.htaccess` for URL rewriting.

Do not introduce another framework or ORM without an explicit requirement.

## 3. Project Structure

Follow the existing directory structure.

```text
app/
├── Controllers/
│   └── API/
│       └── TodoController.php
├── Models/
│   └── Todo.php
├── Kernel.php
└── helpers.php

public/
├── .htaccess
└── index.php

resources/
└── views/
    └── index.php

routes/
├── api.php
└── web.php

.env.example
.gitignore
composer.json
composer.lock
ichi
README.md
LICENSE.md
```

Responsibilities:

- `app/Controllers/`: Application controllers.
- `app/Controllers/API/`: API controllers.
- `app/Models/`: Database models.
- `app/Kernel.php`: Application initialization, database connection, view configuration, and route registration.
- `public/index.php`: Application entry point.
- `public/.htaccess`: Apache URL rewriting.
- `resources/views/`: PHP view templates.
- `routes/web.php`: Browser-facing routes.
- `routes/api.php`: API routes.
- `ichi`: Command-line entry point for Ichi commands.
- `composer.json`: Project dependencies and autoload configuration.

Do not move files or reorganize directories unnecessarily.

## 4. Coding Conventions

### PHP

- Use PHP.
- Follow the existing PSR-4 namespace structure.
- Use `App\` as the application namespace.
- Use `App\Controllers` for controllers.
- Use `App\Controllers\API` for API controllers.
- Use `App\Models` for models.
- Use four spaces for indentation, following the existing application code.
- Use clear and descriptive class, method, and variable names.
- Keep methods focused on a single responsibility.
- Add return types where appropriate and compatible with the project's supported PHP version.
- Avoid unnecessary abstractions and duplicated logic.

Example controller:

```php
namespace App\Controllers\API;

use JiJiHoHoCoCo\IchiRoute\Controller\BaseController;

class ExampleController extends BaseController
{
    public function index()
    {
        // Implementation
    }
}
```

### Models

Application models should extend:

```php
JiJiHoHoCoCo\IchiORM\Database\Model
```

Example:

```php
namespace App\Models;

use JiJiHoHoCoCo\IchiORM\Database\Model;

class Todo extends Model
{
    public $id;
    public $title;
    public $description;
    public $is_completed;
    public $created_at;
    public $updated_at;
}
```

Follow the existing model conventions. Do not introduce another database abstraction layer.

### Routes

Register browser routes in `routes/web.php`.

Register API routes in `routes/api.php`.

Both route files expose functions that receive the existing `Route` instance.

Example:

```php
function api(Route $route): void
{
    // Register API routes here.
}
```

Use the existing IchiRoute APIs and middleware conventions.

Do not create a second router or bypass the application's route registration.

### Controllers and Responses

API controllers should extend the existing `BaseController` when appropriate.

Use the existing `jsonResponse()` helper for JSON API responses.

Follow the existing response structure unless an explicit API contract change is requested.

Current response examples:

```php
return jsonResponse([
    'todos' => Todo::paginate()
]);
```

```php
return jsonResponse([
    'todo' => Todo::find($id)
]);
```

Avoid changing existing response keys without considering API compatibility.

### Views

Store PHP view files in:

```text
resources/views/
```

Use the existing IchiTemplate view functionality.

Keep application presentation separate from database and business logic.

## 5. Current Application Behavior

The current API routes are:

| Method | Endpoint | Controller |
|---|---|---|
| GET | `/api/todos` | `TodoController@index` |
| GET | `/api/todos/{id}` | `TodoController@show` |

The current `Todo` model contains:

- `id`
- `title`
- `description`
- `is_completed`
- `created_at`
- `updated_at`

Treat these as the existing model properties.

Do not assume that database migrations, table creation, authentication, authorization, or additional CRUD endpoints already exist.

Before implementing database changes, inspect the actual database configuration and existing schema if available.

## 6. Environment and Database Configuration

Application configuration is supplied through the root `.env` file. Keep `.env` out of version control and maintain `.env.example` as the safe template for required configuration.

The current intended environment configuration is:

```env
APP_ENV=production

VITE_PORT=5173
SERVER_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo
DB_USERNAME=root
DB_PASSWORD=password
```

Variable guidance:

- `APP_ENV`: Application environment, currently set to `production`.
- `VITE_PORT`: Vite development-server port.
- `SERVER_URL`: Base URL for the application server.
- `DB_CONNECTION`: Selected IchiORM connection name; currently `mysql`.
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`: MySQL connection settings.

Important rules:

- Never commit `.env`.
- Never expose real database credentials or copy production secrets into documentation, examples, logs, or source code.
- Treat values shown in `.env.example` as placeholders, not real credentials.
- Update `.env.example` whenever required environment variables are added, removed, or renamed.
- Do not hardcode environment-specific values in application code.
- Preserve the existing IchiORM connection setup and inspect the installed IchiORM API before changing it.
- Check that each environment variable is actually read by the application before relying on it.
- The current `app/Kernel.php` reads `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`, then selects the connection using `DB_CONNECTION`. It does not currently pass `DB_PORT` to `createConnection()`. Do not claim that a non-default port is supported until this is updated and verified.
- Do not assume that `APP_ENV`, `VITE_PORT`, or `SERVER_URL` affect runtime behavior until their usage is confirmed in the application or build configuration.

Use `composer.lock` to understand the currently installed dependency versions.

Do not update dependencies unnecessarily.

## 7. Security Requirements

Security must be considered when implementing every feature.

- Validate user input before processing it.
- Use IchiValidation for input validation where appropriate.
- Use parameterized database operations through IchiORM.
- Never concatenate untrusted input into SQL statements.
- Escape untrusted values when rendering HTML.
- Protect state-changing operations against CSRF where applicable.
- Apply authentication and authorization before exposing protected operations.
- Do not expose database credentials, internal exceptions, or sensitive application information in API responses.
- Do not trust client-provided identifiers or completion status without validating them.

When adding new endpoints, consider HTTP methods, input validation, response status codes, and error handling.

## 8. API Development Guidelines

Keep the API consistent.

- Use appropriate HTTP methods.
- Use predictable JSON response structures.
- Validate route parameters and request data.
- Handle missing records explicitly.
- Avoid exposing unnecessary database fields.
- Keep controller methods readable.
- Do not silently change existing API response formats.

When adding or modifying endpoints, inspect the current IchiRoute and IchiORM APIs rather than assuming Laravel or other framework conventions.

## 9. Testing and Verification

The repository currently does not define a test suite or Composer development testing scripts.

Do not claim that tests passed if they were not executed.

For every change:

1. Inspect the relevant existing implementation.
2. Check PHP syntax for modified PHP files.
3. Verify the relevant route or application behavior where possible.
4. Verify database-dependent behavior against an available database.
5. Report any checks that could not be performed.

Use PHP syntax checking:

```bash
php -l path/to/file.php
```

Do not introduce a testing framework or additional development dependencies without a clear reason.

When tests are introduced, add regression tests for the new behavior and update the project documentation.

## 10. Dependency Management

Use Composer to manage dependencies.

- Keep `composer.json` and `composer.lock` consistent.
- Do not manually edit `composer.lock`.
- Avoid unnecessary dependency upgrades.
- Prefer existing Ichi packages over introducing duplicate functionality.
- Inspect package documentation or source code when unsure about an API.

Do not assume that Ichi packages have the same methods or behavior as Laravel, Symfony, or other PHP frameworks.

## 11. Change Management

Before modifying code:

1. Inspect the existing implementation.
2. Identify the actual root cause or requirement.
3. Reuse existing project conventions.
4. Make the smallest reasonable change.
5. Avoid unrelated refactoring.
6. Preserve existing functionality.
7. Verify the changes.

Do not:

- Rewrite working code without a reason.
- Introduce unnecessary architecture.
- Add speculative features.
- Change public API behavior unexpectedly.
- Remove existing functionality without explicit justification.
- Modify unrelated files.

When fixing a bug, focus on the affected functionality and its regression coverage.

## 12. Documentation

Update `README.md` when changes affect:

- Installation.
- Environment configuration.
- Application setup.
- Database requirements.
- CLI commands.
- API endpoints.
- Application behavior.

Document actual implemented functionality. Do not document planned features as if they already exist.

## 13. Git Workflow

- Use the existing `main` branch as the default integration branch.
- Keep commits focused on one logical change.
- Use descriptive commit messages.
- Avoid committing generated dependencies, local environment files, or secrets.
- Inspect the Git diff before committing.
- Do not overwrite or discard existing user changes.

## 14. General Instructions for AI Agents

The repository's actual source code is the source of truth.

When working on this project:

- Read relevant files before making assumptions.
- Follow the existing Ichi framework architecture.
- Prefer minimal, maintainable PHP implementations.
- Maintain compatibility with the existing PHP version.
- Avoid unnecessary dependencies.
- Consider security and backward compatibility.
- Explain important implementation decisions.
- Clearly distinguish implemented functionality from planned functionality.

The goal is to develop a clean, secure, maintainable TODO application while preserving the existing Ichi PHP framework architecture.
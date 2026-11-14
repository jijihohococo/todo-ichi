# AGENTS.md

## 1. Project Overview

**todo-ichi** is a task management application built with a PHP MVC backend and a Vue 3 frontend.

The application provides:
- Displaying todo items.
- Creating todo items.
- Editing todo items.
- Deleting todo items.
- Marking todo items as completed or active.
- Searching and filtering todo items.

The backend exposes a JSON REST API, while the frontend communicates with it through the Fetch API.

Repository: https://github.com/jijihohococo/todo-ichi

The primary development branch is `main`.

## 2. Technology Stack

### Backend
- PHP 7.3 or later.
- Ichi ecosystem, including IchiRoute, IchiORM, and IchiValidation.
- MySQL.
- Composer.

### Frontend
- Vue 3 Composition API.
- Vue Router.
- Vite.
- JavaScript.
- Native Fetch API.
- CSS.

## 3. Repository Structure

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
│       │   ├── TodoItem.vue
│       │   ├── TodoList.vue
│       │   ├── TodoModal.vue
│       │   ├── TodoStats.vue
│       │   ├── TodoTable.vue
│       │   └── TodoToolbar.vue
│       ├── router/
│       │   └── index.js
│       ├── services/
│       │   ├── apiClient.js
│       │   └── todoService.js
│       ├── styles/
│       │   └── app.css
│       ├── utils/
│       │   └── todoUtils.js
│       ├── views/
│       │   └── DashboardView.vue
│       ├── App.vue
│       └── main.js
├── public/
│   └── vue/
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

Follow the existing directory structure when adding new features. Avoid placing unrelated responsibilities in existing files.

## 4. Backend Architecture and Conventions

### 4.1 Controllers

Controllers handle HTTP requests and coordinate application logic.

API controllers should:
- Receive and validate request data.
- Delegate database operations to repositories when appropriate.
- Return JSON responses.
- Use suitable HTTP status codes.
- Avoid duplicating database operations already handled by repositories.

Current API controller:

`app/Controllers/API/TodoController.php`

The controller provides:
- `index()` — retrieve all todos, ordered by descending ID.
- `show($id)` — retrieve a todo by ID.
- `create()` — validate and create a todo.
- `update($id)` — validate and update a todo.
- `delete($id)` — delete a todo.

Creation initializes `is_completed` to `false`.

### 4.2 Repositories

Repositories encapsulate data operations.

Current repository:

`app/Repositories/TodoRepository.php`

Interface:

`app/Repositories/TodoRepositoryInterface.php`

The repository interface defines:
- `create(array $attribute)`
- `update(int $id, array $attribute)`
- `delete(int $id)`

When adding repository operations:
- Keep the implementation consistent with the interface.
- Handle missing records appropriately.
- Return consistent JSON responses.
- Use appropriate HTTP status codes.
- Avoid putting presentation or frontend logic in repositories.

### 4.3 Models

Models represent database entities.

Current model:

`app/Models/Todo.php`

The Todo model extends:

`JiJiHoHoCoCo\IchiORM\Database\Model`

Current todo fields include:
- `id`
- `title`
- `description`
- `is_completed`
- `created_at`
- `updated_at`

Use the existing IchiORM conventions for database operations.

### 4.4 Validation

Use IchiValidation for request validation.

Current todo validation rules include:
- `title`: required and string.
- `description`: string.

Validation errors should return HTTP 422 with a JSON response containing a message and validation errors.

Do not trust frontend validation alone. Validate all incoming API data on the backend.

### 4.5 Routes

Routes are defined in:

`routes/web.php`

Current routes:

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/` | Render application page |
| GET | `/api/todos` | Retrieve all todos |
| GET | `/api/todos/{id}` | Retrieve one todo |
| POST | `/api/todos` | Create a todo |
| PUT | `/api/todos/{id}` | Update a todo |
| DELETE | `/api/todos/{id}` | Delete a todo |

API routes use the IchiRoute API middleware.

When adding routes, follow the existing route grouping and middleware conventions.

### 4.6 Kernel and Database Configuration

Application initialization and database configuration are handled by:

`app/Kernel.php`

Database configuration uses environment variables:
- `DB_CONNECTION`
- `DB_HOST`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

`.env.example` also defines `DB_PORT`, but the current Kernel database configuration does not pass this value to the connector. Do not assume changing `DB_PORT` currently changes the connection port without updating the Kernel configuration.

## 5. Frontend Architecture and Component Responsibilities

The frontend uses Vue 3 Composition API.

Keep components focused on their own responsibilities. Avoid duplicating state, utility functions, API operations, or event-handling logic unnecessarily.

### 5.1 DashboardView.vue

Location:

`src/vue/views/DashboardView.vue`

Responsibilities:
- Render the dashboard page structure.
- Display the page header and Add Task button.
- Control whether TodoModal is visible.
- Keep track of the selected todo for editing.
- Open the modal for creating or editing.
- Close the modal.
- Handle the modal's `saved` event.
- Refresh TodoTable after a successful save.

TodoTable is accessed through a component reference. Its `fetchTodos()` method is exposed using `defineExpose()`.

DashboardView should not duplicate TodoTable's todo collection, filtering, or CRUD implementation.

### 5.2 TodoTable.vue

Location:

`src/vue/components/TodoTable.vue`

Responsibilities:
- Own the todo collection.
- Manage loading and error states.
- Fetch todos from the API.
- Manage search and filtering.
- Calculate todo counts.
- Handle completion toggling.
- Handle deletion.
- Emit create and edit events to the parent.
- Expose `fetchTodos()` for the parent component.

TodoTable is responsible for coordinating the todo list and its related operations.

Do not move modal form submission or modal-specific form state into TodoTable.

### 5.3 TodoModal.vue

Location:

`src/vue/components/TodoModal.vue`

Responsibilities:
- Display the create/edit form.
- Manage form input state.
- Manage form validation.
- Manage the saving state.
- Call the appropriate create or update service.
- Emit `close` when the modal should close.
- Emit `saved` after a successful operation.

The modal owns its form submission and editing logic.

Do not put modal form state or save/update implementation in DashboardView.

### 5.4 TodoList.vue

Location:

`src/vue/components/TodoList.vue`

Responsibilities:
- Render loading states.
- Render empty states.
- Render the todo item list.
- Pass todo data to TodoItem.
- Forward relevant events to TodoTable.

TodoList should focus on list presentation and event forwarding.

### 5.5 TodoItem.vue

Location:

`src/vue/components/TodoItem.vue`

Responsibilities:
- Display an individual todo.
- Display its completion state.
- Emit events for completion toggling.
- Emit events for editing.
- Emit events for deletion.

Use the shared `isCompleted()` utility instead of implementing duplicate completion checks.

### 5.6 TodoToolbar.vue

Location:

`src/vue/components/TodoToolbar.vue`

Responsibilities:
- Display the search input.
- Display the filter controls.
- Receive search and filter values through props.
- Emit `update:search` and `update:filter` events.

Keep filtering and todo collection management in TodoTable.

### 5.7 TodoStats.vue

Location:

`src/vue/components/TodoStats.vue`

Responsibilities:
- Display todo statistics.
- Show total, active, and completed counts.

This component should remain presentational. Calculate the values in the parent and pass them through props.

## 6. Frontend Utilities and API Services

### 6.1 Shared Todo Utilities

Location:

`src/vue/utils/todoUtils.js`

Shared utility functions should be implemented here when they are needed by multiple components.

The current `isCompleted(todo)` function recognizes:
- `true`
- `1`
- `"1"`

Import this function wherever completion-state checking is required.

Avoid duplicating the same logic in multiple components.

### 6.2 API Client

Location:

`src/vue/services/apiClient.js`

The API client uses native `fetch()`.

Its response format is:

```javascript
{
  ok: true,
  status: 200,
  data: {}
}
```

Important:
- `ok` indicates whether the HTTP request succeeded.
- `status` contains the HTTP status code.
- `data` contains the parsed JSON response or `null`.

Do not assume Axios response conventions.

Use:

```javascript
if (!response.ok) {
  // Handle the error.
}
```

For API error messages, use:

```javascript
response.data?.message
```

The client handles JSON parsing, including empty or invalid JSON responses.

### 6.3 Todo Service

Location:

`src/vue/services/todoService.js`

All todo API endpoint operations should be centralized here.

Current operations:
- `getTodos()`
- `getTodoById(id)`
- `createTodo(data)`
- `updateTodo(id, data)`
- `deleteTodo(id)`

Components should call these service functions rather than duplicating endpoint URLs and Fetch API implementations.

## 7. Environment, PHP Page, and Vite

### 7.1 Environment Variables

The example environment file is:

`.env.example`

Current variables include:

```env
APP_ENV=development

VITE_PORT=5173
SERVER_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Never commit real database credentials or secrets.

### 7.2 Page Rendering

The PHP page is:

`resources/views/index.php`

In development:
- Load the Vite client.
- Load `src/vue/main.js` through the Vite development server.

In production:
- Load `/vue/main.css`.
- Load `/vue/app.js`.

The application entry point is:

`src/vue/main.js`

The Vue application mounts to:

`#vue-app`

### 7.3 PageController

`app/Controllers/PageController.php` determines whether to use the Vite development server.

Development mode uses `APP_ENV=development` and obtains the Vite server URL from the configured server URL and port, with a localhost fallback.

### 7.4 Vite Configuration

Configuration:

`vite.config.js`

Current conventions:
- Vue plugin.
- `@` alias points to `src/vue`.
- `publicDir: false`.
- Development server listens on `0.0.0.0`.
- Development port is `5173`.
- `strictPort: true`.
- HMR uses `localhost:5173`.
- Production output directory is `public/vue`.
- JavaScript entry output is `app.js`.
- CSS output is `main.css`.

The Vite configuration currently uses port `5173` directly. Do not assume `VITE_PORT` in `.env.example` automatically changes the Vite server port.

### 7.5 NPM Scripts

Use the scripts defined in `package.json`:

```bash
npm run dev
npm run build
```

Do not introduce alternative build commands without a reason.

## 8. Security and Data Handling

- Validate all incoming API data on the backend.
- Do not trust frontend validation.
- Do not expose database credentials or secrets.
- Do not commit `.env` files containing real credentials.
- Use parameterized database operations through the existing ORM.
- Do not concatenate untrusted input into SQL.
- Check record existence before updating or deleting records.
- Return appropriate HTTP status codes.
- Avoid exposing internal exceptions, stack traces, or sensitive configuration in production responses.
- Keep API responses consistent.

## 9. Build, Testing, and Verification

Before completing a change, perform the relevant checks.

### Frontend

Run:

```bash
npm run build
```

Check for:
- Vue compilation errors.
- Missing imports.
- Incorrect component props or events.
- Broken service imports.
- Vite build errors.

### Backend

Check:
- PHP syntax.
- Controller and repository method signatures.
- Route definitions.
- Validation behavior.
- Database operation behavior.
- Compatibility with the supported PHP version.

PHP syntax can be checked with:

```bash
php -l path/to/file.php
```

### Testing

Do not assume that a PHPUnit test suite or a dedicated test script exists. Inspect the current project configuration before attempting to run tests.

When adding tests, follow the project's existing dependencies and conventions.

Do not claim tests passed unless they were actually executed successfully.

## 10. Dependency Management

### PHP

Use Composer for PHP dependencies.

Do not manually modify installed dependency files under `vendor/`.

### JavaScript

Use npm for frontend dependencies.

Do not manually modify installed dependency files under `node_modules/`.

Only add dependencies when necessary. Prefer existing project dependencies and native browser APIs when they are sufficient.

## 11. Change Management

When modifying the application:

1. Inspect the existing implementation before making changes.
2. Follow the current architecture and naming conventions.
3. Keep each component or class responsible for a clear task.
4. Avoid duplicated logic.
5. Reuse existing services and utilities.
6. Preserve existing functionality unless the requested change requires modifying it.
7. Keep changes focused and avoid unrelated refactoring.
8. Check compatibility with the project's supported PHP version.
9. Run relevant build and syntax checks.
10. Explain important changes and any verification limitations.

Do not introduce unnecessary abstractions or additional layers for simple operations.

## 12. Documentation

Keep documentation consistent with the actual implementation.

When changing:
- API endpoints, update API documentation.
- Environment variables, update `.env.example` and relevant setup instructions.
- Dependencies, update the appropriate dependency files.
- Build configuration, update development and production instructions.
- Component responsibilities, update architecture documentation when necessary.

Do not document planned or hypothetical behavior as if it already exists.

## 13. Git Workflow

The primary branch is:

`main`

Before committing:
- Check the current branch.
- Review modified files.
- Avoid committing generated files or local secrets unless explicitly required.
- Keep commits focused and descriptive.

Use pull requests for changes intended to be reviewed or merged.

Do not claim that changes have been pushed, committed, or merged unless the corresponding Git operation succeeded.

## 14. General Instructions for AI Agents

When working on this project:

- Treat the current repository implementation as the source of truth.
- Inspect relevant files before proposing code changes.
- Prefer complete, directly usable code when the user requests implementation.
- Preserve existing naming conventions and architecture.
- Keep Vue component responsibilities separated.
- Keep API operations in service modules.
- Keep shared utilities in the utility directory.
- Keep database operations in the model/repository layer according to existing conventions.
- Avoid unnecessary duplication.
- Do not assume dependencies, scripts, tests, or features exist without checking.
- Do not invent file contents or claim unverified behavior.
- Explain any assumptions that materially affect the implementation.
- Prioritize minimal, correct, maintainable changes over unnecessary complexity.
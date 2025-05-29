# Learn Mi'gmaq Online

This document provides a high-level overview of the system architecture, directory structure, and guidance for adding or modifying features.

## Overview

This is a PHP-based educational content management system designed to support the creation, management, and delivery of language learning material. The application has a public-facing side (for students/users) and an administrative dashboard (for content contributors/admins).

### Core Features

* User authentication and registration (public sign-up can be enabled or disabled by an admin)
* Unit > Section > Lesson hierarchy
* Rich-text editing using SunEditor
* Drag-and-drop reordering of units, sections, and lessons
* Audio file uploading and inline playback
* Role-based access control (admin/contributor)
* Settings management by admins

## Project Structure

```
├── public/                 # Public entrypoint (index.php)
├── controllers/            # Application logic (MVC controllers)
├── models/                 # ORM-like model layer (Unit, Section, Lesson, User, etc.)
├── views/                  # HTML templates with Bootstrap 5 and SunEditor
├── includes/
│   ├── init.php            # DB setup, session, default schema
│   └── helpers.php         # Utility functions (e.g., isAdmin, getSetting)
└── data/                   # SQLite data file (data.db)
```

## Routing

Routes are declared in `public/index.php` as a map of HTTP method → URI → handler function.

Example:

```php
'GET' => [
    '/contents' => fn() => (new ContentsController($pdo))->show(),
]
```

## Authentication & Authorization

* **AuthController.php** handles login, registration, logout.
* User roles: `admin`, `contributor` (defined in the `users` table).
* Admin-only features include user management and settings control.

Session variables:

```php
$_SESSION['user_id']
$_SESSION['username']
$_SESSION['role']
```

Helper:

```php
isAdmin(): bool
```

## Content Structure

The content hierarchy is:

* Unit → Section → Lesson

Each has:

* `title`, `body`, `status`, `position`
* Create/edit/delete interfaces (via modals)
* Rich text powered by SunEditor
* Reorderable via SortableJS

Controller/Model pairing:

| Type    | Controller        | Model   |
| ------- | ----------------- | ------- |
| Unit    | UnitController    | Unit    |
| Section | SectionController | Section |
| Lesson  | LessonController  | Lesson  |

Each dashboard page follows this logic:

1. Fetch parent entity (unit/section)
2. Display editable list
3. Submit forms to POST routes to save/delete

## Dashboard

* Route: `/dashboard`
* Nav links to:

  * `/dashboard/unit-editor`
  * `/dashboard/section-editor?unitId=...`
  * `/dashboard/lesson-editor?unitId=...&sectionId=...`
  * `/dashboard/manage-users` (admin only)

The navbar breadcrumbs auto-adjust based on context.

## Audio Uploads

* Upload audio: `POST /audio/upload`
* Stream audio: `GET /audio?id=...`
* Stored in `audios` table as BLOBs (inline with `SunEditor`)

## Admin Controls

* Admin-only view at `/dashboard/manage-users`
* User management: create, update, delete, reset password, change role
* Settings management (e.g., toggle `registration_enabled`)

## Testing & Development Notes

* SQLite database is defined in `includes/init.php`
* All schema creation is auto-executed on startup
* Default registration is enabled; first registered user is `admin`
* `SunEditor` and `SortableJS` are included via CDN + `/src/`
* Bootstrap 5 and Bootstrap Icons are used for UI

## Adding New Features

1. **Controller:** Create a new controller class in `controllers/`.
2. **Model (optional):** Create or extend a model class in `models/`.
3. **View:** Add or modify HTML templates in `views/`.
4. **Route:** Register your route in `public/index.php`.
5. **Permissions:** Use `isAdmin()` and `ensureAuthenticated()` to protect routes.
6. **Data:** Use `$pdo` to run queries or extend a model class.

## Helpful Functions

From `includes/helpers.php`:

```php
isAdmin(): bool
getSetting(PDO $pdo, string $key, string $default): string
setSetting(PDO $pdo, string $key, string $value): void
getAllSettings(PDO $pdo): array
```

## Contributing

When working on a feature:

* Keep code modular (follow MVC separation)
* Escape user inputs in views using `htmlspecialchars`
* Use prepared statements for all DB interactions
* Follow existing UI conventions and styling

## Questions?

Talk to the maintainer or check the relevant controller/view.

Happy coding! 🎉

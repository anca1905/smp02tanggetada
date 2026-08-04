# AGENTS.md

## Project overview

SIMS (Sistem Informasi Manajemen Sekolah) — a school management platform with two codebases in one repo:

1. **Laravel 10 web app** (root) — server-rendered Blade views + Sanctum API for the mobile app
2. **Flutter mobile app** (`mobile/`) — student/parent client, talks to the Laravel API via Sanctum bearer tokens

There is also `promosi/` (a standalone PHP landing page, not part of Laravel) and `docs/` (a standalone PHP documentation site).

## Stack

- PHP 8.1+, Laravel 10, MySQL 8, Vite 5, Tailwind CSS 4 (via `@tailwindcss/vite` plugin)
- Flutter (Dart SDK >=3.4), Provider for state management, Dio for HTTP
- Linter: `laravel/pint` (PHP), `flutter_lints` (Dart)
- Tests: PHPUnit 10 (PHP), `flutter_test` (Dart)
- Auth API: Laravel Sanctum (bearer tokens for mobile)

## Dev commands

```bash
# Laravel
php artisan serve                    # start dev server (port 8000)
npm run dev                          # vite dev server (HMR for CSS/JS)
npm run build                        # production assets
php artisan migrate                  # run migrations
php artisan db:seed                  # seeds via InitialDataSeeder
php artisan migrate:fresh --seed     # full reset + seed

# Tests
php artisan test                     # all PHPUnit tests
php artisan test --filter=TestName   # single test
./vendor/bin/phpunit                 # alternative

# Lint (PHP)
./vendor/bin/pint                    # auto-fix PSR-12 style

# Flutter (run from mobile/)
flutter pub get
flutter run
flutter test
flutter analyze                      # dart linter
```

## Authentication — multi-guard system

The app uses **4 separate auth guards**, each with its own model and database table. This is the most non-obvious architectural detail:

| Guard      | Model      | Table        | Login field  | Web prefix    |
|------------|------------|--------------|--------------|---------------|
| `web`      | `User`     | `users`      | (unused)     | —             |
| `operator` | `Operator` | `operators`  | `username`   | `/tu/*`       |
| `teacher`  | `Teacher`  | `teachers`   | `username`   | `/teacher/*`  |
| `student`  | `Student`  | `students`   | `nis`        | `/student/*`  |

- Operators with `role_operator` = `"Kepala Sekolah"` or `"principal"` redirect to `/principal/dashboard` (still uses `auth:operator` guard).
- Web login (`/login`) accepts `operator` or `teacher` roles only. Students log in via the mobile API (`POST /api/student/login`).
- Parents have a separate login endpoint (`POST /api/student/parent-login`), credential is `parent_password` on the `students` table.
- The `operator()` global helper (`app/Helpers/operator.php`) returns the current operator user.

## Seeder credentials (InitialDataSeeder)

| Role     | Username/NIS | Password   |
|----------|-------------|------------|
| Admin    | `admin`     | `password` |
| Teacher  | `guru`      | `password` |
| Students | `120001`–`120030` | `password` |

## Directory structure (key paths)

```
app/Http/Controllers/
├── AdminTu/        # Admin TU (operator) controllers — 16 controllers
├── Api/            # Sanctum API for mobile (StudentAuth, StudentApi, AttendanceCheckin, BillingApi)
├── Auth/           # Single AuthController for web login/logout
├── Principal/      # Principal dashboard (uses operator guard)
├── Student/        # Student web LMS (LearningController)
├── Teacher/        # Teacher dashboard, presence, teaching/LMS, promotion, settings
├── PublicController.php      # Landing page, news, contact, schedule, calendar
├── StudentPresenceController.php  # QR-based student attendance
└── GraduationController.php

resources/views/
├── layouts/        # 4 Blade layouts: app, public, student, tu
├── tu/             # Admin TU views
├── teacher/        # Teacher views
├── student/        # Student web views
├── public/         # Public-facing pages
├── auth/           # Login form
└── landing.blade.php

mobile/lib/
├── providers/      # AuthProvider, DataProvider (ChangeNotifier)
├── screens/        # Flutter screens
├── services/       # ApiService (Dio + Sanctum token)
├── theme/          # AppTheme
├── utils/          # Constants (API base URL)
└── widgets/
```

## Gotchas

- **Mobile API base URL is hardcoded** to a production VPS (`https://sims.skillance.cloud/api`) in `mobile/lib/utils/constants.dart`. Change it when developing locally.
- **PHPUnit does not use SQLite in-memory** — the sqlite config lines in `phpunit.xml` are commented out. Tests run against the real MySQL database by default.
- **No CI/CD** pipeline exists — no `.github/workflows/`, no pre-commit hooks.
- **`update_parents.php`** is a one-off script (not an artisan command) that sets `parent_password = bcrypt('ortu' + NIS)` for students missing one. Run with `php update_parents.php`.
- **`tailwind.config.js` exists but Tailwind v4 is configured via the Vite plugin** (`@tailwindcss/vite`). The config file may be vestigial — the Vite plugin handles Tailwind processing.
- **File uploads** use the `local` filesystem disk (not S3). Uploaded files go to `storage/app/`.
- **Blade views reference Tailwind utility classes** — keep `resources/` in the content scan paths if editing Tailwind config.
- The `promosi/` and `docs/` directories are standalone PHP pages, not part of the Laravel routing or autoloader.

# Habit Builder API

A RESTful API built with Laravel 12 for helping users build and track daily habits.

The system allows users to create habits, track daily progress, monitor streaks, and manage their personal habit history.

---

## Features

### Authentication

- User Registration
- User Login & Logout
- Laravel Sanctum Authentication

### Habits

- Create Habit
- Update Habit
- Delete Habit
- View Personal Habits
- Search Habits
- Pagination

### Habit Tracking

- Mark Habit as Completed
- Daily Progress Tracking
- Prevent Duplicate Completion
- Automatic Streak Calculation

### General

- API Resources
- Form Request Validation
- Standard JSON Responses
- Eloquent Relationships

---

## Tech Stack

- PHP 8
- Laravel 12
- Laravel Sanctum
- MySQL
- REST API

---

## Main Models

- User
- Habit
- HabitLog

---

## Authentication

The API uses Laravel Sanctum.

Protected endpoints require:

```
Authorization: Bearer YOUR_TOKEN
```

---

## Installation

```bash
git clone https://github.com/ahmaddura49-rgb/habit-builder-api.git

cd habit-builder-api

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

---

## Future Improvements

- Habit Categories
- Reminder Notifications
- Statistics Dashboard
- Achievement System
- Unit & Feature Tests

---

## Author

**Ahmad Dura**

Backend Developer (Laravel)

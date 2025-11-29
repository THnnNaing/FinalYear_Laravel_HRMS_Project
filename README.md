<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# Installation 🛠️

Installing and running this project is super easy! Please follow the steps below and you'll be ready to rock 🤘.

---

### Prerequisites

* **PHP** (version 8.0+)
* **Composer**
* **Node.js** and **Yarn** (or npm)

---

## Getting Started

1.  **Clone the Repository**

    Open your terminal and clone the project from GitHub:

    ```bash
    git clone [https://github.com/THnnNaing/FinalYear_Laravel_HRMS_Project.git]
    cd FinalYear_Laravel_HRMS_Project
    ```

2.  **Install PHP Dependencies**

    Use the following command to install all necessary PHP packages:

    ```bash
    composer install
    ```

3.  **Configure Environment**

    Copy the example environment file and generate a unique application key:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Install Frontend Dependencies**

    ```bash
    npm install
    npm run dev
    # or npm install && npm run dev
    ```

5.  **Install Laravel UI Assets**

    ```bash
    composer require laravel/ui --dev
    php artisan breeze:install
    php artisan ui bootstrap
    ```

6.  **Database Migration**

   Set up your database tables (in .env):

    ```bash
    php artisan migrate:refresh --seed 
    ```
    
7.  **Serve Program**

   Server ON url: http://127.0.0.1:8000/

    ```bash
    php artisan serve
    ```

    You can now view your application at **http://127.0.0.1:8000**.

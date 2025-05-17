# 📱 Product Master List App

A Laravel + Vue web application that allows users to manage a list of products by uploading an Excel file. Includes pagination, real-time search, file validation, and toast notifications.

---

## Features

-  Upload `.xlsx` Excel files to update product quantity
-  Add or deduct quantity based on status (`Buy` / `Sold`)
-  Pagination with correct item numbering
-  Search by Product ID
-  Toast notifications for upload success/error
-  Styled with TailwindCSS

---

## Tech Stack

- Backend: Laravel 10
- Frontend: Vue 3 + Vite
- Styling: Tailwind CSS
- Excel Parsing: `maatwebsite/excel`
- Notifications: `vue-toastification`

---

## Installation Steps

1. Clone the repository:

   git clone https://github.com/kkgan82/CompAsia.git
   cd your-repo-name

2. Install PHP dependencies:
   composer install

3. Install JavaScript dependencies:
   npm install

4. Copy the environment file:
   cp .env.example .env

   Then open `.env` and configure your database settings:

   DB_DATABASE=product_inventory
   DB_USERNAME=root
   DB_PASSWORD=

5. Generate the Laravel application key:

   php artisan key:generate

6. Run database migrations:

   php artisan migrate

7. Build frontend assets for production:

   npm run build

8. Start the Laravel development server:

   php artisan serve

9. Open your browser and visit:

   http://localhost:8000
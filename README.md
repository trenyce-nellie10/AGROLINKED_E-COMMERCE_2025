# AgroLinked

AgroLinked — a lightweight farm-to-table e-commerce starter built with PHP (MVC-style), PDO, and minimal JS.  
This repo contains vendor, admin, and customer modules: product CRUD, vendor approval, orders, cart checkout, and image uploads.

---

## Table of Contents

- [Quickstart (XAMPP)](#quickstart-xampp)
- [Database](#database)
- [Project Structure](#project-structure)
- [Sample Accounts](#sample-accounts)
- [Environment & Configuration](#environment--configuration)
- [Git: Create repo & push (quick commands)](#git-create-repo--push-quick-commands)
- [Payment Integration (mockup)](#payment-integration-mockup)
- [Security & Production Notes](#security--production-notes)
- [Troubleshooting](#troubleshooting)
- [Next steps & Suggestions](#next-steps--suggestions)

---

## Quickstart (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/).
2. Start **Apache** and **MySQL** services.
3. Place the project folder into XAMPP's `htdocs` (e.g. `C:\xampp\htdocs\agrolinked`).
4. Open phpMyAdmin: `http://localhost/phpmyadmin`.
5. Create a database named `agrolinked_db` or import `agrolinked_schema.sql` directly (if you have it).
6. Import the SQL schema:
   - In phpMyAdmin select the database → Import → choose `agrolinked_schema.sql` → Execute.
7. Ensure the `uploads/` folder exists and is writable by the webserver (Windows usually OK; on Linux run `chmod -R 775 uploads`).
8. Open the app: `http://localhost/agrolinked/`.

---

## Database

The project uses the schema in `agrolinked_schema.sql`. Key tables:

- `users` — admins, vendors, customers
- `vendor_profiles` — business metadata & `approved` flag
- `categories` — product categories
- `products` — product catalog
- `product_images` — extra product images
- `cart` — server-side cart
- `orders` / `order_items` — orders

If you change DB credentials, update `db/db.php`.

---

## Project Structure (important files)


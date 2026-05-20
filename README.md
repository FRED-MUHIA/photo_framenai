# nairobi-printers

NaiPrinters - Web-to-Print eCommerce (Laravel + Blade + Tailwind + Alpine)

NaiPrinters is a complete online printing platform inspired by onlineprinters-style workflows. It supports product configuration, live pricing, artwork uploads, cart/checkout, customer dashboard, and admin operations.

## Core Features

- Homepage with hero, category grid, popular products slider, trust badges, testimonials, and quick quote section
- Product catalog with category filters and search
- Dynamic product configurator:
  - Size / paper / finishing / print-side options
  - Quantity matrix pricing
  - Real-time quote API (`/api/quote`)
  - Delivery-time estimate
- Artwork upload system:
  - PDF/PNG/JPG/WEBP/AI validation
  - Drag-and-drop upload UX
  - Queue job for metadata and DPI checks
  - Saved artworks per account (or guest session)
- Cart and checkout:
  - Session cart for guests and users
  - Coupon support
  - Shipping options (standard/express/pickup)
  - Payment method selection (Stripe, PayPal, M-Pesa, COD placeholders)
  - Order and order-items creation
- Customer dashboard:
  - Order history
  - Reorder
  - Invoice page
  - Saved designs
- Admin backend:
  - Dashboard stats
  - Category CRUD
  - Product CRUD
  - Pricing matrix + configurator-spec JSON management
  - Order management
  - Artwork review workflow
  - Customer management
  - Coupon CRUD
- Extras:
  - WhatsApp live chat button
  - Multi-language toggle scaffold (`en`, `sw`)
  - SEO-ready metadata and schema.org organization markup

## Tech Stack

- Laravel 10.x
- Blade templates
- TailwindCSS + Alpine.js
- MySQL / MariaDB
- Laravel Queue (sync default)

## Setup

1. Install dependencies

```bash
composer install
npm install
```

2. Configure environment

- `.env` is already configured for MySQL.
- Create the configured database before running migrations.

3. Run migrations + seeders

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

For production/cPanel MySQL deployments, create a MySQL database and user in
cPanel, grant the user access to the database, then update the deployed `.env`
with those exact credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpaneluser_database
DB_USERNAME=cpaneluser_dbuser
DB_PASSWORD=your_database_password

php artisan config:clear
php artisan migrate --seed
```

If you see a `forge@localhost` error, Laravel is still missing the live MySQL
credentials or is using a stale cached config. Recheck `.env`, then run
`php artisan config:clear`.

4. Build assets and serve

```bash
npm run dev
php artisan serve
```

## Seeded Accounts

- Admin user:
  - Email: `admin@naiprinters.test`
  - Password: `password`

## Important Routes

- Storefront: `/`
- Products: `/products`
- Upload artwork: `/upload-artwork`
- Cart: `/cart`
- Checkout: `/checkout`
- Customer dashboard: `/dashboard`
- Admin dashboard: `/admin`
- Quote API: `POST /api/quote`

## Database Modules

- `users` (role-aware: customer/admin)
- `categories`
- `products`
- `product_option_groups`
- `product_option_values`
- `product_quantity_prices`
- `artworks`
- `coupons`
- `orders`
- `order_items`

## Notes for Production Hardening

- Replace payment placeholders with real gateway SDK/API integrations
- Move artwork files to S3 in `config/filesystems.php`
- Use Redis queue and workers for upload processing
- Add policy classes for stricter ownership checks
- Add feature tests for cart/checkout/admin workflows
- Add image optimization/CDN for media performance
# Nai-Printaz

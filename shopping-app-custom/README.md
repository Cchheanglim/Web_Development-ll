# MarketHub — Laravel Shopping Website (Alibaba-style redesign)

> **Already have this project running from an earlier version?** This update adds an
> Alibaba-style redesign (orange branding, category browsing, hero banner, product
> gallery, "buy box") plus a new `category` column on products. Copy these files over
> your existing `app/`, `database/`, and `resources/` folders as before, then run:
> ```
> php artisan migrate:fresh --seed
> ```
> `migrate:fresh` drops and rebuilds every table (safe here since this is a class
> project with only seeded/demo data) so the new `category` column and the updated
> seeder data line up cleanly. If you have real data you want to keep, use
> `php artisan migrate` instead — it will just add the new column, but your existing
> products will show category "Others" until you edit them.


A full-stack shopping/marketplace web app built with **PHP + Laravel** and **Tailwind CSS**,
featuring buyer/seller/admin roles, product listings with image galleries, search & filter,
and an in-app buyer↔seller chat tied to each product.

---

## 1. Tech Stack

- PHP 8.1+ / Laravel 10.x
- MySQL 8 (or MariaDB / PostgreSQL — adjust `.env`)
- Blade templates + Tailwind CSS (via CDN, no build step needed)
- Session-based auth (Laravel's built-in `Auth` facade + bcrypt hashing)

## 2. Project Structure (what's in this download)

This zip contains **only the application-layer files** you need to add on top of a
fresh Laravel skeleton (the `vendor/`, `bootstrap/cache/`, and Laravel's own framework
files are not included — they're pulled in by Composer). This keeps the download small
and avoids shipping a huge, immediately-stale `vendor` folder.

```
app/Http/Controllers/        Controllers (Auth, Product, Chat, Admin, Dashboard)
app/Http/Middleware/         RoleMiddleware for role-based access control
app/Models/                  User, Product, ProductImage, Chat
config/database.php          Stock Laravel config, with one fix (see below)
database/migrations/         Migrations matching the ERD
database/seeders/            Sample users + products
resources/views/             Blade templates (layout, auth, products, dashboard, chat, admin)
routes/web.php                All application routes
.env.example                 Environment template
```

> **Note on `config/database.php`:** this is otherwise Laravel's stock file — the only
> change is one line in the `mysql` connection's `options` array, which avoids a
> "Constant PDO::MYSQL_ATTR_SSL_CA is deprecated" notice on PHP 8.5+. Safe to copy
> straight over your existing one.

## 3. Setup Instructions (step-by-step)

### Step 1 — Create a fresh Laravel project
```bash
composer create-project laravel/laravel shopping-app "^10.0"
cd shopping-app
```

### Step 2 — Copy in the files from this download
Copy everything from this package **into** your new `shopping-app/` folder, overwriting
`routes/web.php` and merging the `app/`, `database/`, and `resources/` folders.

```bash
cp -r path/to/downloaded/app/*        shopping-app/app/
cp -r path/to/downloaded/database/*   shopping-app/database/
cp -r path/to/downloaded/resources/*  shopping-app/resources/
cp path/to/downloaded/routes/web.php  shopping-app/routes/web.php
cp path/to/downloaded/config/database.php shopping-app/config/database.php
```

### Step 3 — Configure your database
Copy `.env.example` from this package over Laravel's own `.env.example` values (or just
edit your `.env` directly):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopping_app
DB_USERNAME=root
DB_PASSWORD=
```

Create the database (`shopping_app`) in MySQL, then generate your app key:
```bash
php artisan key:generate
```

### Step 4 — Register the role middleware
Laravel 10 registers middleware aliases in `app/Http/Kernel.php`. Open it and add this
line inside the `$middlewareAliases` (or `$routeMiddleware`) array:

```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

### Step 5 — Storage link (for uploaded product images)
```bash
php artisan storage:link
```

### Step 6 — Run migrations + seeders
```bash
php artisan migrate --seed
```

This creates all tables and seeds just one account:
- 1 admin: `admin@example.com` / `password` (the only account that has to be seeded —
  there's no self-registration path for admins by design)

Everything else is empty on purpose. Register your own buyer and seller accounts at
`/register`, then post real products (with your own photos) from a seller account via
"+ Post product". This gives you full control over what's in the database instead of
demo data getting in the way.

### Step 7 — Serve the app
```bash
php artisan serve
```
Visit **http://127.0.0.1:8000**

---

## 4. Feature Overview

| Module | What it does |
|---|---|
| **Auth** | Register/login/logout. Signup lets the user pick Buyer or Seller. Admin accounts are seeded, not self-registered. |
| **Role middleware** | `role:seller`, `role:admin` etc. protect routes; buyers can't post products, only admins reach `/admin/*`. |
| **Products** | Sellers: create/edit/delete their own products with multiple image uploads and a category. Everyone: browse/search/filter by keyword, category, condition, price range. |
| **Product detail** | Real photo gallery with hover arrows, thumbnails, and a full-screen zoom lightbox. Star rating, seller card linking to their storefront, reviews list, related products. |
| **Orders / checkout** | Buyers click "Buy now" → a real checkout page (quantity stepper with live total, shipping details, payment method choice) → places a pending order. Sellers confirm/complete/cancel from their dashboard; buyers can cancel while still pending. Each order has a receipt page with a visual status tracker. **This is not a payment system** — no money moves through the app, by design (see note below). |
| **Reviews & ratings** | Once an order is marked completed, its buyer can leave a 1–5 star rating + comment. Rolled into an average shown on cards, the product page, and the seller's storefront. |
| **Favorites / wishlist** | Any logged-in user can heart a product to save it; buyers see saved items on their dashboard. |
| **Seller storefronts** | Every seller has a public page (`/sellers/{id}`) — name, join date, average rating, full catalog. Click a seller's name anywhere to get there. |
| **Chat** | Thread scoped to (product, buyer, seller). Messages stored in `chats` table. The page auto-refreshes (polling) every few seconds so it feels live without needing a websocket server — see "Going further" below for true real-time. |
| **Dashboards** | Seller: own products, orders received, buyer messages. Buyer: orders placed, conversations, favorites. |
| **Admin** | Manage (list/edit/delete) all users and all products from one panel. User/product/order counts shown. |

## 5. Database (matches the ERD in the brief)

```
users            id, name, email, password, role (buyer|seller|admin), timestamps
products         id, user_id -> users.id, title, description, price, condition, category, timestamps
product_images   id, product_id -> products.id, image_path, timestamps
chats            id, buyer_id -> users.id, seller_id -> users.id, product_id -> products.id, message, timestamps
orders           id, product_id -> products.id, buyer_id -> users.id, seller_id -> users.id, quantity, total_price, status, shipping_name, shipping_phone, shipping_address, payment_method, timestamps
reviews          id, order_id -> orders.id (unique), product_id -> products.id, buyer_id -> users.id, rating, comment, timestamps
favorites        id, user_id -> users.id, product_id -> products.id, timestamps (unique per user+product)
```

## 6. Routes

```
GET  /                                Home / featured products
GET  /register  POST /register        AuthController@showRegister / register
GET  /login     POST /login           AuthController@showLogin / login
POST /logout                          AuthController@logout
GET  /products                        ProductController@index   (search & filter)
GET  /products/create                 ProductController@create      [seller]
POST /products                        ProductController@store       [seller]
GET  /products/{product}              ProductController@show
GET  /products/{product}/edit         ProductController@edit        [seller, owner]
PUT  /products/{product}              ProductController@update      [seller, owner]
DELETE /products/{product}            ProductController@destroy     [seller, owner]
GET  /dashboard                       DashboardController@index     [auth]
GET  /sellers/{seller}                SellerController@show
GET  /chat/{product}/{seller}         ChatController@show           [auth]
POST /chat/{product}/{seller}         ChatController@store          [auth]
GET  /products/{product}/checkout     OrderController@checkout      [auth]
POST /products/{product}/orders       OrderController@store         [auth]
GET  /orders/{order}                  OrderController@show          [auth]
PATCH /orders/{order}/status          OrderController@updateStatus  [auth]
POST /orders/{order}/review           ReviewController@store        [auth]
POST /products/{product}/favorite     FavoriteController@toggle     [auth]
GET  /admin                           AdminController@dashboard     [admin]
GET  /admin/users                     AdminController@users         [admin]
GET  /admin/users/{user}/edit         AdminController@editUser      [admin]
PUT  /admin/users/{user}              AdminController@updateUser    [admin]
DELETE /admin/users/{user}            AdminController@destroyUser   [admin]
GET  /admin/products                  AdminController@products      [admin]
DELETE /admin/products/{product}      AdminController@destroyProduct[admin]
```

## 7. About the "Buy Now" flow — why there's no real payment

Your original brief only asked for buyers to browse and chat with sellers — there was
no checkout in the ERD. Since a *class marketplace project* handling real card numbers
or bank transfers raises real financial and security obligations (PCI compliance, fraud,
liability) that aren't appropriate to wire up casually, "Buy Now" here creates a
**request-to-buy order** the seller must confirm — like an offer, not a transaction. No
payment gateway is involved.

If you genuinely need real payments for your assignment, the standard next step is
integrating **Stripe Checkout** (`stripe/stripe-php` via Composer): you'd redirect the
buyer to a Stripe-hosted payment page after they click "Buy now", and mark the order
`confirmed` once Stripe's webhook reports a successful charge. That's a meaningful
scope increase (Stripe account, API keys, webhook handling) — ask if you want me to
build that next.

## 8. How close is this to the real Alibaba.com?

Close in *flow* — browse, filter by category, view a rated product with reviews and a
seller storefront, save favorites, buy or chat, track an order status — but genuinely
not, and not trying to be, a full clone. Real Alibaba runs on things that are out of
scope for a class project, on purpose:

- **Real payments & escrow** (Trade Assurance) — needs a licensed payment processor,
  not something to bolt on casually (see the note above)
- **RFQ / bulk quote negotiation** — buyers requesting custom quotes from multiple
  suppliers — a genuinely different feature, not a checkout variant
- **Logistics/freight tracking, multi-currency, supplier verification tiers,
  live chat translation** — each is its own subsystem with real infrastructure behind it

If any specific one of these actually matters for your assignment, say which one and
I'll scope it properly rather than trying to guess at "everything, perfectly" — that's
also how you avoid an assignment that looks impressive but doesn't run, or that drowns
the actual grading rubric (roles, CRUD, chat, validation) under half-finished extras.

## 9. Going further (nice-to-haves, not included to keep this beginner-friendly)

- Swap the chat's AJAX polling for real WebSocket push using **Laravel Reverb** or
  Pusher + Laravel Echo.
- Add a `orders` table for actual checkout/purchase flow.
- Move validation into dedicated `FormRequest` classes (`StoreProductRequest`, etc.) —
  the controllers currently validate inline with `$request->validate()`, which is
  intentionally simple for a class project, but is worth "graduating" to Form Requests.
- Add Stripe/PayPal for real payments.
- Add product categories and reviews/ratings.

## 10. Git & deployment (per the assignment)

```bash
git init
git add .
git commit -m "Initial Laravel shopping platform"
```
Push to GitHub/GitLab, then deploy to Hostinger, SiteGround, or any shared/VPS PHP
host that supports Laravel (PHP 8.1+, Composer, MySQL). Remember to run
`php artisan migrate --seed`, `php artisan storage:link`, and set `APP_ENV=production`,
`APP_DEBUG=false` on the live server.

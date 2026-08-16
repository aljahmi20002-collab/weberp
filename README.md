# WebERP / WebPOS

**WebERP** is a full-featured **ERP + POS** (Enterprise Resource Planning &
Point of Sale) web application built with **Laravel 10** and the
[`nwidart/laravel-modules`](https://nwidart.com/laravel-modules) package. It is
designed for small-to-medium businesses that need to manage multiple companies,
multiple branches, inventory, sales, purchases, accounting, HR, and support
tickets in one system.

It ships with both a **web admin panel** (Blade + Bootstrap) and a **REST API**
(`/api/v1/`) authenticated with [Laravel Sanctum](https://laravel.com/docs/sanctum),
making it usable by mobile/third-party clients.

---

## ✨ Features

### Multi-company / Multi-branch (SaaS-ready)
- A **Superadmin** manages the whole platform (plans, subscriptions, languages,
  settings).
- An **Admin / Business Owner** can register and own a **Business**, create
  multiple **Branches**, and invite **Users** (employees) into specific
  branches.
- Each business has its own currency, logo, settings, plans & subscription.
- Granular **subscriptions / plans** — features are gated per plan.

### Users & Permissions
- Role-based access control (RBAC) with fine-grained permissions
  (`*_read`, `*_create`, `*_update`, `*_delete`, plus feature-specific
  permissions like `pos_add_payment`).
- Custom per-user permission overrides.
- User banning / status toggling.
- Activity log (spatie/laravel-activitylog) and login-activity tracking.
- Social login (Google / Facebook) via Socialite.
- Email verification / OTP, password reset, reCAPTCHA.

### Inventory (Products / Stock)
- Products with multiple **variations** (size, color, …).
- Categories (with parent/child), Brands, Units, Warranties, Tax rates.
- Per-branch stock tracking via `VariationLocationDetails`.
- **Stock transfers** between branches.
- Barcode generation (milon/barcode).
- **Bulk import** from Excel (maatwebsite/excel).

### Sales
- **Regular sales** (invoices) + **POS screen** for walk-in customers.
- **Service sales** (non-stock services).
- **Sale proposals / quotations**.
- Per-sale payments (cash / bank / card), multiple payments per invoice.
- Invoicing & print views (with mobile-friendly print).
- Discounts, order tax, shipping charges.
- Customer management (walk-in or registered).

### Purchases
- Purchase orders from suppliers.
- **Purchase returns** with their own payments.
- Per-branch purchase tracking.
- Supplier management.

### Accounting
- **Accounts** (cash / bank) at business or branch level, with a default
  account for sales and purchases.
- **Account Heads** (chart of accounts).
- **Incomes** and **Expenses** against accounts.
- **Fund transfers** between accounts.
- **Bank transactions** ledger tying every sale/purchase/income/expense/transfer
  to an account.

### HRM (Human Resources)
- Departments & Designations.
- Duty schedules.
- Weekends & public holidays.
- Leave types, leave assignment per role, apply-for-leave workflow with
  approve/reject.
- **Attendance** (Check-in / Check-out) with status (Present / Absent / Not
  checked out).
- Attendance reports per employee/date range.

### Reports
- Attendance reports
- Profit & loss report
- Product-wise profit (sales & POS)
- Expense reports
- Customer sales / POS reports
- Purchase reports, purchase-return reports
- Stock reports
- Supplier reports
- Service-sale reports
- Reports are accessible through the web panel **and** the API.

### Other
- **Multi-language** with editable translation phrases from the admin panel
  (supports RTL / LTR per language).
- **Backup** (database download).
- **CRUD generator** (generates model/migration/controller/views from a form).
- **Support ticket system** with chat (admin-to-admin & user-to-admin).
- **Assets management** (assets + asset categories).
- **Payment gateways**: Stripe and Skrill are integrated.
- **Image processing** with intervention/image (original + 3 thumbnails).
- General, mail, login, reCAPTCHA, and payment settings editable from admin.
- Toastr + SweetAlert notifications, Yajra DataTables for listing tables.

---

## 🧱 Tech Stack

| Layer       | Technology                                                            |
|-------------|-----------------------------------------------------------------------|
| Language    | PHP ≥ 8.1                                                             |
| Framework   | Laravel 10.x                                                          |
| Modularity  | nwidart/laravel-modules v10                                           |
| Auth        | Laravel UI, Laravel Sanctum (API), Socialite (Google/Facebook)        |
| Frontend    | Blade, Bootstrap 5, jQuery, Vite                                      |
| Database    | MySQL / MariaDB                                                       |
| Images      | intervention/image                                                    |
| Excel       | maatwebsite/excel (bulk import)                                       |
| Barcode     | milon/barcode                                                         |
| Activity    | spatie/laravel-activitylog                                            |
| DataTables  | yajra/laravel-datatables                                              |
| Payments    | Stripe (stripe/stripe-php), Skrill (obydul/laraskrill)                 |
| CAPTCHA     | anhskohbo/no-captcha                                                   |
| Env editor  | geo-sot/laravel-env-editor                                            |

---

## 📁 Project Structure

```
app/                  # Core application code (outside of modules)
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/       # REST API (auth, dashboard, users, sales, reports ...)
│   │   ├── Auth/         # Register / Login / Forgot-password / Social auth
│   │   └── Backend/      # Admin-panel controllers (profile, users, roles,
│   │                     #                 settings, backup, languages, ...)
│   ├── Helpers/          # Global helper functions (helper.php, settings.php ...)
│   ├── Middleware/       # XSS, CheckApiKey, PermissionCheck, Localization
│   ├── Requests/         # Form-Request validation classes
│   └── Resources/        # API Resources
├── Models/
│   └── Backend/          # Role, Permission, Setting, Language, Upload, ...
├── Repositories/         # Repository interfaces & implementations for core modules
└── Traits/               # ApiReturnFormatTrait, CommonHelperTrait

Modules/                # 44 feature modules — each one is a mini-Laravel
├── Installer/            # Web-based installer
├── Business/             # Companies (businesses)
├── Branch/               # Branches
├── Subscription/ + Plan/ # SaaS plans & subscriptions
├── Product/, Variation/, Brand/, Category/, Unit/, Warranties/, TaxRate/
├── Pos/, Sell/, ServiceSale/, SaleProposal/, Service/
├── Purchase/             # Purchases + purchase returns
├── StockTransfer/
├── Customer/, Supplier/
├── Account/, AccountHead/, Income/, Expense/, FundTransfer/
├── Department/, Designation/, DutySchedule/, Attendance/, LeaveType/,
│   LeaveAssign/, ApplyLeave/, Holiday/, Weekend/
├── Reports/
├── Support/              # Support tickets + chat
├── Assets/, AssetCategory/
├── BulkImport/
├── BusinessSettings/
└── Currency/

config/                 # Application configuration
database/
├── migrations/           # Core migrations (users, roles, permissions, settings ...)
└── seeders/              # Core seeders (roles, permissions, default settings, users)
resources/views/        # Blade views (auth, backend layouts, installable overrides)
routes/
├── web.php               # Web routes
└── api.php               # API routes (/api/v1/...)
public/                 # Public assets
```

---

## 🚀 Quick Start — One Click (Docker)

The easiest way to run WebERP / WebPOS without installing PHP, MySQL, or
anything else is with Docker Compose.

### 1. Install Docker
- **Windows / macOS**: install [Docker Desktop](https://docs.docker.com/get-docker/)
- **Linux (Ubuntu/Debian)**:
  ```bash
  sudo apt update && sudo apt install -y docker.io docker-compose-plugin
  sudo usermod -aG docker $USER && newgrp docker
  ```

### 2. Start everything (one command!)

**Linux / macOS**:
```bash
./start.sh
```

**Windows**:
```cmd
start.bat
```
or in PowerShell:
```powershell
.\start.ps1
```

Or manually with Docker Compose directly:
```bash
docker compose up -d --build
```

That's it! The script will:
1.  Build a Docker image with PHP 8.2 + all required extensions, Composer, Node
2.  Start a MySQL 8 container (persistent data in a Docker volume)
3.  Start phpMyAdmin at `http://localhost:8081`
4.  Automatically run `composer install`, `npm install`, `npm run build`
5.  Generate an `APP_KEY` and write `.env` if missing
6.  Run migrations + seeders (first run only)
7.  Start Nginx on `http://localhost:8080` and the Laravel dev server on `http://localhost:8000`
8.  Open your browser automatically 🎉

### URLs after start

| Service           | URL                          |
|-------------------|------------------------------|
| **Web App (Nginx)** | http://localhost:8080       |
| Web App (Artisan) | http://localhost:8000        |
| **phpMyAdmin**    | http://localhost:8081        |

### Useful commands
```bash
./start.sh             # Start / restart (Linux/Mac)
./start.sh stop        # Stop all containers
./start.sh --reset     # WIPE database and start fresh
docker logs -f weberp-app   # View live logs
docker compose exec app bash    # Shell into the PHP container
```

Default database credentials inside Docker: **host=`db`, db=`weberp`, user=`weberp`, pass=`secret`** (root pass: `secret`). Change them in `docker-compose.yml` before first run for production.

---

## 🧪 Local dev start (without Docker)

If you have PHP 8.1+, Composer, Node 16+ and MySQL already installed:

```bash
./dev.sh
```
Or follow the manual steps below.

---

## 🔧 Manual Installation

### Requirements
- PHP ≥ 8.1
- MySQL / MariaDB
- Composer
- Node.js + NPM (for building frontend assets)
- Required PHP extensions: `mysqli`, `gd`, `curl`, `bcmath`, `ctype`, `fileinfo`,
  `zip`, `mbstring`, `tokenizer`, `xml`, `json`, `openssl`, `pdo`, `mbstring`,
  `intl` (recommended).
- `allow_url_fopen = On` and a valid `date.timezone` set in `php.ini`.

### Step-by-step

1. **Clone the repository**
   ```bash
   git clone https://github.com/aljahmi20002-collab/weberp.git
   cd weberp
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install & build frontend assets**
   ```bash
   npm install
   npm run build
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Edit `.env` and set your database credentials, mail driver, etc.

5. **Run the web installer**

   Visit your application URL in a browser. You will be redirected to
   `/install`, where you can:
   1. Confirm server requirements
   2. Enter database host/name/username/password
   3. Create the first **Superadmin** account (name, email, password)

   The installer will run migrations + seeders automatically and flag the app
   as installed (`APP_INSTALLED=yes`).

   If you prefer the command line instead:
   ```bash
   php artisan migrate --seed
   ```
   Then set `APP_INSTALLED=yes` in `.env`. You can then log in with a
   superadmin you create via tinker, or run the installer once to set it up.

6. **Create a storage symlink & set permissions**
   ```bash
   php artisan storage:link
   chmod -R 775 storage bootstrap/cache
   ```

7. **(Optional) Run the queue worker**
   ```bash
   php artisan queue:work
   ```

### Post-installation

- Log in with the superadmin account you created during installation.
- Configure **General settings**, **Currency**, **Plans** under Superadmin panel.
- Register a new Business account to see the admin flow, or sign in with a
  business-level user.

---

## 🔐 Default Demo Accounts (seeded)

After running `db:seed` you will get demo accounts. **All of them are created
with random 16–20 character passwords** (generated each time you seed) so that
the seed data is not exploitable if accidentally used in production. To look up
the password for a local/dev account, use:

```bash
php artisan tinker
>>> App\Models\User::where('email','admin@weberp.app')->update(['password'=>bcrypt('password')]);
```

The Superadmin account created via the web installer is controlled by the
password you enter during setup and is always safe.

---

## 🌐 REST API

- Base URL: `/api/v1/`
- Authentication:
  - Send header `apiKey: <API_KEY>` (matches `API_KEY` in `.env`) on **every**
    request.
  - After login, send the Sanctum token as `Authorization: Bearer <token>`.

### Endpoints (summary)

| Method | Route                              | Description                              |
|--------|------------------------------------|------------------------------------------|
| POST   | `/api/v1/login`                    | Login (email/phone + password)           |
| POST   | `/api/v1/register`                 | Register new business + admin user       |
| POST   | `/api/v1/resend-otp`               | Resend email-verification OTP            |
| POST   | `/api/v1/verify-now`               | Verify email with OTP                    |
| POST   | `/api/v1/reset-password-otp`       | Request password-reset OTP               |
| POST   | `/api/v1/reset-password`           | Reset password with OTP                  |
| GET    | `/api/v1/countries`                | List currencies / countries              |
| — Auth required —                                           |                                            |
| POST   | `/api/v1/logout`                   | Revoke current token                     |
| GET    | `/api/v1/refresh`                  | Refresh token (revokes old ones)         |
| GET    | `/api/v1/profile`                  | Current user profile                     |
| GET    | `/api/v1/permissions`              | Current user permissions                 |
| — Subscribed users only —                                   |                                            |
| GET    | `/api/v1/dashboard/summery-count`  | Dashboard count cards                    |
| GET    | `/api/v1/dashboard/sales-charts`   | Sales charts data                        |
| GET    | `/api/v1/dashboard/purchase-charts`| Purchase charts data                     |
| GET|POST|PUT|DELETE `/api/v1/employee/*`      | Employee CRUD                            |
| GET|POST|PUT|DELETE `/api/v1/project/*`       | Project CRUD                             |
| GET|POST|PUT|DELETE `/api/v1/todo/*`          | Todo list CRUD                           |
|        | `/api/v1/sales`, `/sale/customers`, `/branch_list/{id}`, `/branch_wise_products/{id}`, `/tax_list`, `/get_tax/{id}` | Sales + helpers |
|        | `/api/v1/*_reports` (POST)         | Reports (attendance, profit/loss, expense, stock, sale, purchase, product-sale-profit, customer-sale/pos, service-sale) |

A Postman collection is included at the project root:
`we_erp.postman_collection.json`.

---

## 🔑 Security Notes

- The `/install*` routes are protected by the `IsNotInstalled` middleware —
  once `APP_INSTALLED=yes` they redirect to `/`, so they cannot be used to
  wipe a production database.
- Purchase-code (Envato) verification has been removed.
- All CRUD-generator inputs are validated against strict allow-lists to
  prevent command injection; automatic `migrate --force` from the generator is
  disabled.
- Default demo/seeded accounts use random per-seed passwords.
- An `XSS` middleware strips tags from non-exempt inputs.
- CSRF protection is enabled for web routes.

**Recommended hardening before exposing to the internet:**
- Set `APP_DEBUG=false` and `APP_ENV=production` in `.env`.
- Set a strong, random `API_KEY` (e.g. 64 random characters).
- Use HTTPS, enable HSTS, configure secure session cookies.
- Remove the `Installer` module entirely after installation if you don't plan
  to reuse it.
- Run `php artisan config:cache` (after this, `env()` calls outside of config
  files will stop working — the codebase has been migrated to use
  `config('app.demo_mode')` already).
- Set up rate limiting on login/API endpoints.

---

## 🧪 Testing

```bash
php artisan test
```
The default Example tests are shipped. Contributions should add feature tests
for new modules.

---

## 🧩 Modules

Each module under `Modules/` follows the standard nwidart structure:

```
Modules/<Name>/
├── Config/
├── Console/
├── Database/
│   ├── Migrations/
│   └── Seeders/
├── Entities/          # Eloquent models
├── Enums/
├── Http/
│   ├── Controllers/   # Web + Api controllers
│   ├── Middleware/
│   ├── Requests/      # Form requests
│   └── Resources/     # API resources (per module where applicable)
├── Providers/
├── Repositories/      # Repository interfaces + implementations
├── Resources/
│   ├── assets/
│   ├── lang/
│   └── views/
├── Routes/
│   ├── web.php
│   └── api.php
├── Tests/
├── module.json
└── composer.json
```

Enabled / disabled modules are tracked in `modules_statuses.json` at the project
root.

---

## 📝 License & Credits

Originally developed by **NebrasERP** and released under the MIT license (see
`composer.json`). This repository is a redistribution / extended copy — see the
Git history for full contributors.

If you find this project useful, consider contributing improvements back
upstream.

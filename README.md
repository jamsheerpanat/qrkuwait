# QRKuwait SaaS - Installation & Deployment Guide

### 🖥 Kitchen & Packing Operations
- **KDS**: Accessible via `/admin/kds`. Designed for fullscreen tablet/TV use. Auto-polls new orders every 5s with optional sound alerts.
- **Packing Mode**: Accessible via `/admin/packing`. tailored for grocery stores with `Picking -> Packed -> Ready` workflow.

### 📊 Reporting & POS
- **Dashboard**: Daily totals, revenue tracking, and top-selling items.
- **Exports**: Filtered order exports in CSV format for accounting.
- **POS API**: Secure JSON endpoint at `/api/pos/orders`.
  - **Auth**: Pass `X-API-KEY` header (generated in Store Settings).
  - **Filter**: Use `?since=2025-12-01` to pull incremental updates.
  - **Example Request**:
    ```bash
    curl -X GET \
      'https://yourdomain.com/api/pos/orders?since=2025-12-01' \
      -H 'Accept: application/json' \
      -H 'X-API-KEY: your_generated_api_key'
    ```

### ⚙️ Feature Toggles
Enable or disable modules in **Settings > Operational Features**:
- KDS / Packing Screen
- Delivery / Pickup modes
- Item Variants & Modifiers
- WhatsApp Automatic Notifications

---

*Built with ❤️ for QuickFood & QRKuwait* is a production-ready Laravel 10+ SaaS application designed for high-performance restaurant and grocery QR ordering. It is optimized for Hostinger Shared Hosting (PHP 8.x, MySQL).

## Key Features
- **Multi-tenant Routing**: Automatic store resolution via `/{tenant_slug}`.
- **Role-based Access**: Super Admin, Tenant Admin, Cashier, Kitchen.
- **Premium UI**: Modern, card-based, mobile-first design using Tailwind CSS & Alpine.js.
- **Shared Hosting Ready**: No Node.js runtime or Websocket dependencies.

---

## Local Setup

1. **Clone & Install**
   ```bash
   composer install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database**
   ```bash
   # Create database (MySQL or SQLite)
   php artisan migrate --seed
   ```

---

## Hostinger Deployment Steps

Since Hostinger Shared Hosting usually lacks Node.js for production builds, follow these steps:

### 1. Build Assets Locally
On your local machine, run:
```bash
npm install
npm run build
```
Ensure the `public/build` directory is generated.

### 2. Upload Files
Upload all project files to your Hostinger `public_html` (or a subdirectory) via FTP/File Manager.
**CRITICAL**: Ensure the `.env` file is uploaded and configured for your Hostinger MySQL database.

### 3. Move Public Files (Optional but Recommended)
If you want the app to run from `yourdomain.com` instead of `yourdomain.com/public`:
1. Move all contents of the `public/` folder to `public_html/`.
2. Update `index.php` in `public_html/` to point to the new paths:
   ```php
   require __DIR__.'/../vendor/autoload.php'; // Update to correct path
   $app = require_once __DIR__.'/../bootstrap/app.php'; // Update to correct path
   ```

### 4. Configuration (SSH or File Manager)
- Set Folder Permissions:
  - `storage/` and `bootstrap/cache/` must be writable (**775** or **777**).
- Run Migrations (via Hostinger SSH or a temporary route):
  ```bash
  php artisan migrate --force
  ```

### 5. Cron Job (Required)
Set up a Cron Job in Hostinger Panel to run every minute:
```bash
/usr/local/bin/php /home/uXXXXX/public_html/artisan schedule:run >> /dev/null 2>&1
```

---

## .env Example (Hostinger MySQL)
```env
APP_NAME=QRKuwait
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://qrkuwait.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_qrdb
DB_USERNAME=u123456789_qruser
DB_PASSWORD=YourStrongPassword123!

QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_DRIVER=file
```

## Troubleshooting
- **404 Invalid Slug**: Ensure the tenant exists in the `tenants` table and the status is set to `active`.
- **Permission Denied**: Check recursive write permissions on `storage`.
- **CSS not loading**: Ensure `public/build` exists and `ASSET_URL` is configured if using a CDN/Subdomain.

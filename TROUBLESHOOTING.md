# Troubleshooting Guide: Logo/Cover & Order Display Issues

## ✅ Issues Fixed

### 1. **Logo and Cover Banner Not Showing**

**Root Cause:**
The `getSetting()` method in the Tenant model wasn't properly handling the loaded `customSettings` relationship, causing it to return null for logo and cover URLs.

**Solution Applied:**
- Fixed the `getSetting()` method to check if the relationship is loaded
- Properly handles both Collection (loaded) and QueryBuilder (not loaded) cases
- Updated `getSettingUrl()` to handle both full URLs and storage paths

**Code Changes:**
```php
// app/Models/Tenant.php
public function getSetting($key, $default = null)
{
    if ($this->relationLoaded('customSettings')) {
        $setting = $this->customSettings->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
    
    $setting = $this->customSettings()->where('key', $key)->first();
    return $setting ? $setting->value : $default;
}
```

### 2. **Order Show Page 500 Error**

**Root Causes:**
1. Missing `payment_method`, `payment_status`, and `payment_screenshot` in Order fillable fields
2. Empty `statusLogs` relationship causing blade errors

**Solutions Applied:**
- Added new payment fields to Order model's `$fillable` array
- Changed `@foreach` to `@forelse` in order timeline to handle empty logs
- Added fallback message when no status logs exist

---

## 🔧 Additional Steps Required

### 1. **Ensure Storage Link Exists**

Run this command on both local and production:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`, allowing uploaded images to be accessible via the web.

### 2. **Verify Logo/Cover Upload**

1. Go to Super Admin → Tenants → Edit a tenant
2. Upload a logo and cover image
3. Check that files are saved in `storage/app/public/`
4. Verify the `tenant_settings` table has entries for 'logo' and 'cover'

### 3. **Database Check**

Ensure these columns exist in the `orders` table:

```sql
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'orders' 
AND COLUMN_NAME IN ('payment_method', 'payment_status', 'payment_screenshot');
```

If missing, run:

```bash
php artisan migrate
```

---

## 🐛 Common Issues & Solutions

### Issue: Images still not showing after fix

**Check 1: Storage Link**
```bash
# Verify the symlink exists
ls -la public/storage

# If not, create it
php artisan storage:link
```

**Check 2: File Permissions**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**Check 3: Database Values**
```sql
SELECT * FROM tenant_settings WHERE `key` IN ('logo', 'cover');
```

Values should be either:
- Full URL: `https://example.com/image.jpg`
- Storage path: `logos/abc123.jpg` (without 'storage/' prefix)

### Issue: Order page still shows 500 error

**Check 1: Error Logs**
```bash
tail -f storage/logs/laravel.log
```

**Check 2: Relationship Loading**
Ensure the controller loads relationships:
```php
$order = Order::with(['items', 'statusLogs.user'])->findOrFail($id);
```

**Check 3: OrderStatusLog Model**
Verify the model exists and has proper relationships:
```php
// app/Models/OrderStatusLog.php
public function user()
{
    return $this->belongsTo(User::class, 'changed_by_user_id');
}
```

---

## 📋 Deployment Checklist

When deploying to Hostinger:

- [ ] Push code: `git push origin main`
- [ ] SSH into server
- [ ] Pull latest: `git pull origin main`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Clear cache: `php artisan optimize:clear`
- [ ] Set permissions: `chmod -R 775 storage bootstrap/cache`
- [ ] Test logo/cover display
- [ ] Test order show page
- [ ] Check error logs if issues persist

---

## 🔍 Debugging Commands

### Check if storage link exists:
```bash
ls -la public/ | grep storage
```

### Check uploaded files:
```bash
ls -la storage/app/public/
```

### Check tenant settings:
```bash
php artisan tinker
>>> $tenant = App\Models\Tenant::first();
>>> $tenant->load('customSettings');
>>> $tenant->logo_url;
>>> $tenant->cover_url;
```

### Test order loading:
```bash
php artisan tinker
>>> $order = App\Models\Order::with(['items', 'statusLogs.user'])->first();
>>> $order->statusLogs;
```

---

## ✅ Verification Steps

1. **Logo/Cover Test:**
   - Visit tenant public page: `/{tenant_slug}`
   - Check browser console for 404 errors on images
   - Inspect image src attribute

2. **Order Page Test:**
   - Go to Admin → Orders
   - Click on any order
   - Page should load without errors
   - Timeline should show status updates or "No updates yet"

3. **Payment Fields Test:**
   - Create a new order with KNET payment
   - Upload payment screenshot
   - Verify it appears in admin order view

---

## 📞 Still Having Issues?

If problems persist after applying these fixes:

1. Check `storage/logs/laravel.log` for detailed error messages
2. Enable debug mode temporarily: `APP_DEBUG=true` in `.env`
3. Clear all caches: `php artisan optimize:clear`
4. Restart PHP-FPM (if applicable)
5. Check file permissions on storage directories

---

**Last Updated:** 2025-12-19
**Version:** 1.0

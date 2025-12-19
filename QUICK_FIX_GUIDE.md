# Quick Fix Guide: Logo/Cover & Order Page Issues

## 🔍 **Root Cause Analysis**

### Issue #1: Logo & Cover Not Showing
**Status:** ✅ **NOT A BUG** - Images haven't been uploaded yet!

**Findings:**
- The code is working correctly
- The `Tenant` model properly loads settings
- The storage link exists
- **The problem:** No logo/cover has been uploaded to the tenant settings

**Solution:** Upload logo and cover through the Admin Settings page

### Issue #2: Order Show Page 500 Error
**Status:** ⚠️ **NEEDS TESTING** - Cannot reproduce locally (no orders exist)

**Possible Causes:**
1. Missing `user` relationship in `OrderStatusLog` model
2. Null values in status logs
3. Database inconsistency

**Solution Applied:** Added `@forelse` safety check in the view

---

## 📋 **Step-by-Step Fix Instructions**

### For Logo & Cover Display:

#### Step 1: Login as Tenant Admin
```
URL: http://localhost:8000/login
or: https://app.qrkuwait.com/login
```

#### Step 2: Navigate to Settings
```
Admin Dashboard → Settings (in sidebar)
or direct: /admin/settings
```

#### Step 3: Upload Images
1. **Store Logo:**
   - Click "Choose File" under "Store Logo"
   - Select a square image (recommended: 512x512px)
   - Formats: JPG, PNG (max 1MB)

2. **Cover Banner:**
   - Click "Choose File" under "Cover Banner"
   - Select a wide image (recommended: 1920x400px)
   - Formats: JPG, PNG (max 2MB)

#### Step 4: Save Settings
- Click "Save Settings" button at the bottom
- Wait for success message
- Refresh the public storefront page

#### Step 5: Verify
```bash
# Check if files were uploaded
ls -la storage/app/public/branding/

# Check database
php artisan tinker
>>> $tenant = App\Models\Tenant::first();
>>> $tenant->load('customSettings');
>>> $tenant->customSettings;
>>> $tenant->logo_url;
>>> $tenant->cover_url;
```

---

### For Order Show Page Error:

#### Step 1: Check OrderStatusLog Model
Verify the file exists and has proper relationships:

```bash
cat app/Models/OrderStatusLog.php
```

Should contain:
```php
public function user()
{
    return $this->belongsTo(User::class, 'changed_by_user_id');
}

public function order()
{
    return $this->belongsTo(Order::class);
}
```

#### Step 2: Test with Tinker
```bash
php artisan tinker
>>> $order = App\Models\Order::with(['items', 'statusLogs.user'])->first();
>>> $order->statusLogs;
```

If this throws an error, check the `OrderStatusLog` model.

#### Step 3: Check Error Logs
```bash
tail -f storage/logs/laravel.log
```

Then try to access an order page and see the exact error.

---

## 🧪 **Testing Checklist**

### Local Testing:
- [ ] Create a test order from the public storefront
- [ ] Login as admin
- [ ] Go to Orders → View order
- [ ] Check if page loads without errors
- [ ] Upload logo and cover in Settings
- [ ] Visit public storefront
- [ ] Verify logo and cover are displayed

### Production Testing (Hostinger):
- [ ] SSH into server
- [ ] Pull latest code: `git pull origin main`
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear cache: `php artisan optimize:clear`
- [ ] Login as tenant admin
- [ ] Upload logo and cover
- [ ] Test order show page
- [ ] Check error logs if issues persist

---

## 🎯 **Expected Behavior**

### Logo & Cover:
- **Before Upload:** Empty/blank (no images shown)
- **After Upload:** Images appear on public storefront
- **Storage Location:** `storage/app/public/branding/`
- **Database:** `tenant_settings` table with keys 'logo' and 'cover'

### Order Show Page:
- **With Status Logs:** Timeline shows all status changes
- **Without Status Logs:** Shows "No status updates yet" message
- **Never:** Should not show 500 error

---

## 🔧 **Quick Commands**

### Upload Test Images (for development):
```bash
# Create test images directory
mkdir -p storage/app/public/branding

# Download sample images (if you have wget)
wget https://via.placeholder.com/512 -O storage/app/public/branding/logo.png
wget https://via.placeholder.com/1920x400 -O storage/app/public/branding/cover.png

# Insert into database
php artisan tinker
>>> $tenant = App\Models\Tenant::first();
>>> App\Models\TenantSetting::updateOrCreate(
...     ['tenant_id' => $tenant->id, 'key' => 'logo'],
...     ['value' => 'branding/logo.png']
... );
>>> App\Models\TenantSetting::updateOrCreate(
...     ['tenant_id' => $tenant->id, 'key' => 'cover'],
...     ['value' => 'branding/cover.png']
... );
```

### Create Test Order (for testing order page):
```bash
php artisan tinker
>>> $tenant = App\Models\Tenant::first();
>>> $order = App\Models\Order::create([
...     'tenant_id' => $tenant->id,
...     'order_no' => 'TEST001',
...     'customer_name' => 'Test Customer',
...     'customer_mobile' => '12345678',
...     'delivery_type' => 'pickup',
...     'subtotal' => 10.000,
...     'total' => 10.000,
...     'status' => 'pending',
...     'payment_method' => 'cash',
...     'payment_status' => 'pending'
... ]);
>>> echo "Order created with ID: " . $order->id;
```

---

## 📸 **Screenshots to Verify**

### Settings Page Should Show:
1. Logo upload field with preview (if uploaded)
2. Cover upload field with preview (if uploaded)
3. "Save Settings" button

### Public Storefront Should Show:
1. Logo in header/navigation
2. Cover banner at top of page
3. Menu items below

### Order Show Page Should Show:
1. Order details (items, customer info)
2. Status timeline (or "No updates yet")
3. Update status form
4. No 500 errors

---

## ❓ **Still Not Working?**

### If Logo/Cover Still Not Showing:
1. Check browser console for 404 errors
2. Verify storage link: `ls -la public/storage`
3. Check file permissions: `chmod -R 775 storage`
4. Clear browser cache (Ctrl+Shift+R)
5. Check `tenant_settings` table in database

### If Order Page Still Shows 500:
1. Enable debug mode: `APP_DEBUG=true` in `.env`
2. Check exact error in `storage/logs/laravel.log`
3. Verify `OrderStatusLog` model exists
4. Check database for orphaned records
5. Test with a fresh order

---

**Last Updated:** 2025-12-19 16:30
**Status:** Ready for testing

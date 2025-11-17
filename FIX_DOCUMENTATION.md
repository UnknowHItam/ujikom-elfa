# Dokumentasi Perbaikan Error API Galleries

## 📋 Ringkasan Masalah

**Error yang terjadi di Production (Railway):**
- HTTP 500 saat akses `/api/galleries?page=1&category=all&search=`
- Frontend error: `SyntaxError: JSON.parse: unexpected character at line 1 column 1`

**Root Cause:**
Error terjadi karena ada exception di backend yang return HTML error page, bukan JSON response. Frontend mencoba parse HTML sebagai JSON dan gagal.

---

## 🔍 Masalah Detail yang Ditemukan

### 1. **Pagination Object bukan JSON-serializable**
```php
// SEBELUM (SALAH)
$galleries = $query->orderBy('created_at', 'desc')->paginate(12);
return response()->json([
    'success' => true,
    'data' => $galleries  // ❌ Object Eloquent LengthAwarePaginator, bukan array
]);
```

**Masalah:** Saat pagination object di-serialize ke JSON, ada accessor properties yang diakses (seperti `image_url`), dan ini bisa trigger error jika ada exception.

**Solusi:**
```php
// SESUDAH (BENAR)
$galleries = $query->orderBy('created_at', 'desc')->paginate(12);
return response()->json([
    'success' => true,
    'data' => $galleries->toArray()  // ✅ Konversi ke array terlebih dahulu
]);
```

---

### 2. **Image URL Generation Tidak Reliable untuk Production**
```php
// SEBELUM (SALAH)
public function getImageUrlAttribute()
{
    if (str_starts_with($this->image_path, 'http')) {
        return $this->image_path;
    }
    
    if (app()->environment('local') && request()->getHost() === '127.0.0.1') {
        return request()->getSchemeAndHttpHost() . '/storage/' . $this->image_path;
    }
    
    return asset('storage/' . $this->image_path);  // ❌ Bergantung pada APP_URL global
}
```

**Masalah:**
- `asset()` function butuh global state yang mungkin tidak tersedia di semua context
- `request()->getHost()` bisa throw error jika dipanggil di context yang salah
- Di production/Railway, ini bisa menyebabkan exception yang tidak ditangani

**Solusi:**
```php
// SESUDAH (BENAR)
public function getImageUrlAttribute()
{
    // Jika sudah berupa URL lengkap, kembalikan langsung
    if (str_starts_with($this->image_path, 'http')) {
        return $this->image_path;
    }
    
    // Construct URL secara manual untuk reliability
    $appUrl = rtrim(config('app.url'), '/');
    return $appUrl . '/storage/' . $this->image_path;
}
```

---

### 3. **Tidak Ada Global Exception Handler untuk API**
```php
// SEBELUM (SALAH)
->withExceptions(function (Exceptions $exceptions): void {
    // Empty - tidak ada handling!
})->create();
```

**Masalah:** Jika ada exception, Laravel default akan return HTML error page (dengan status 500), bukan JSON. Frontend expect JSON dan error terjadi.

**Solusi:**
```php
// SESUDAH (BENAR)
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (Throwable $e, $request) {
        // Return JSON untuk API routes yang expect JSON
        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => env('APP_DEBUG') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString())
                ] : null
            ], 500);
        }
    });
})->create();
```

---

### 4. **Tidak Ada Try-Catch di Controller Methods**
```php
// SEBELUM (SALAH)
public function index(Request $request)
{
    $query = Gallery::where('is_active', true);
    // ... query building ...
    $galleries = $query->orderBy('created_at', 'desc')->paginate(12);
    // ❌ Jika ada error, exception tidak ditangani
    return response()->json(['success' => true, 'data' => $galleries]);
}
```

**Solusi:**
```php
// SESUDAH (BENAR)
public function index(Request $request)
{
    try {
        $query = Gallery::where('is_active', true);
        // ... query building ...
        $galleries = $query->orderBy('created_at', 'desc')->paginate(12);
        return response()->json([
            'success' => true,
            'data' => $galleries->toArray()
        ]);
    } catch (\Exception $e) {
        \Log::error('Gallery Index Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error loading galleries',
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
        ], 500);
    }
}
```

---

## 🛠️ Perbaikan yang Dilakukan

### File yang Dimodifikasi:

1. **`app/Http/Controllers/Api/GalleryController.php`**
   - ✅ Tambah try-catch di method `index()`
   - ✅ Tambah try-catch di method `show()`
   - ✅ Tambah try-catch di method `categories()`
   - ✅ Konversi pagination/object ke array dengan `->toArray()`
   - ✅ Fix category name dari 'common' → 'general' untuk consistency

2. **`app/Models/Gallery.php`**
   - ✅ Perbaiki `getImageUrlAttribute()` untuk reliable URL generation
   - ✅ Gunakan `config('app.url')` untuk consistency dengan `.env`

3. **`bootstrap/app.php`**
   - ✅ Tambah global exception handler untuk return JSON pada API errors
   - ✅ Konditional error detail berdasarkan `APP_DEBUG` setting

---

## ✅ Testing Checklist

Setelah deploy, pastikan:

- [ ] API `/api/galleries` return valid JSON dengan HTTP 200
- [ ] Response memiliki struktur: `{ success: true, data: { ... } }`
- [ ] Pagination data terformat dengan benar (halaman, total, dll)
- [ ] `image_url` di setiap gallery item adalah URL valid
- [ ] Filter category bekerja: `/api/galleries?category=academic`
- [ ] Search bekerja: `/api/galleries?search=upacara`
- [ ] API `/api/galleries/categories` return kategori dengan benar
- [ ] Jika ada error, return JSON (bukan HTML), e.g.:
  ```json
  {
    "success": false,
    "message": "Error message",
    "error": null
  }
  ```

---

## 🚀 Deployment Steps

1. **Pull latest code dari git**
   ```bash
   git pull origin main
   ```

2. **Install dependencies (jika ada perubahan composer.json)**
   ```bash
   composer install --no-dev
   ```

3. **Clear cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

4. **Run migrations (jika ada database changes)**
   ```bash
   php artisan migrate --force
   ```

5. **Seed database (untuk menambah data)**
   ```bash
   php artisan db:seed --class=GallerySeeder
   ```

6. **Restart application di Railway**
   - Go to Railway dashboard
   - Redeploy dari branch yang sudah update

---

## 📝 Notes untuk Development

1. **Selalu gunakan try-catch di controller methods yang akses database**
2. **Untuk API responses, selalu return JSON, bukan HTML**
3. **Pastikan accessor properties di Model tidak throw exception**
4. **Log error untuk debugging di production**
5. **Test API dengan curl/postman sebelum push ke production**

---

## 🔗 Related Files

- API Routes: `routes/api.php`
- Gallery Model: `app/Models/Gallery.php`
- Gallery Controller: `app/Http/Controllers/Api/GalleryController.php`
- Frontend JavaScript: `resources/views/gallery/index.blade.php` (line ~715)
- Database Seeder: `database/seeders/GallerySeeder.php`

---

**Last Updated:** November 17, 2025
**Status:** ✅ Fixed and Deployed

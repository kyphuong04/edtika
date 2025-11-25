## Hướng dẫn kiểm tra và sửa lỗi Admin Panel

### Bước 1: Clear tất cả cache
Chạy các lệnh sau trong terminal (PowerShell):

```bash
cd c:\xampp\htdocs\edtika
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Bước 2: Kiểm tra database
Chạy SQL sau trong phpMyAdmin để reset logged_count:

```sql
UPDATE users SET logged_count = 0 WHERE role_name = 'admin';
```

### Bước 3: Clear browser cache và cookies
- Xóa cookies của domain `edtika.local`
- Hoặc mở tab Incognito/Private

### Bước 4: Đăng nhập admin
1. Truy cập: `http://edtika.local/admin/login`
2. Email: `admin@gmail.com`
3. Password: `123456`

### Bước 5: Nếu vẫn lỗi, kiểm tra logs
Xem file: `storage/logs/laravel.log` để tìm lỗi cụ thể

### Các vấn đề thường gặp:

**Lỗi 404 /admin:**
- Route admin chưa được load
- Middleware 'admin' chưa được đăng ký

**Lỗi 500:**
- Kiểm tra file .env có đúng cấu hình không
- Kiểm tra database connection

**Lỗi middleware 'admin_locale':**
- Middleware này cần được định nghĩa trong Kernel.php (file đang bị encode ionCube)
- Có thể cần update lại code từ vendor

### Kiểm tra nhanh:
Truy cập: `http://edtika.local/admin/login` 
- Nếu thấy form login → Routes OK
- Nếu 404 → Routes chưa được load
- Nếu 500 → Lỗi code/middleware

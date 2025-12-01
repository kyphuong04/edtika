# Hướng dẫn bật OPcache trong XAMPP

## Bước 1: Mở file php.ini

1. Mở **XAMPP Control Panel**
2. Click vào nút **Config** bên cạnh **Apache**
3. Chọn **PHP (php.ini)**

Hoặc mở trực tiếp file: `C:\xampp\php\php.ini`

## Bước 2: Tìm và sửa cấu hình OPcache

### Cách 1: Tìm kiếm thủ công

Nhấn `Ctrl+F` và tìm từ khóa: `opcache`

### Cách 2: Tìm section [opcache]

Tìm section có chữ `[opcache]` hoặc các dòng có chứa `opcache`

## Bước 3: Sửa các dòng sau

Tìm và **BỎ COMMENT** (xóa dấu `;` ở đầu dòng) và sửa giá trị:

```ini
; 1. Bật OPcache extension
zend_extension=opcache

; 2. Bật OPcache
opcache.enable=1
opcache.enable_cli=1

; 3. Cấu hình memory
opcache.memory_consumption=128
opcache.interned_strings_buffer=8

; 4. Số file tối đa
opcache.max_accelerated_files=10000

; 5. Revalidation
opcache.revalidate_freq=2

; 6. Performance
opcache.fast_shutdown=1
opcache.validate_timestamps=1
```

### Ví dụ TRƯỚC khi sửa:
```ini
;zend_extension=opcache
;opcache.enable=0
```

### Ví dụ SAU khi sửa:
```ini
zend_extension=opcache
opcache.enable=1
```

## Bước 4: Lưu file php.ini

Nhấn `Ctrl+S` để lưu file

## Bước 5: Restart Apache

1. Quay lại **XAMPP Control Panel**
2. Click nút **Stop** bên cạnh **Apache**
3. Đợi Apache dừng hoàn toàn
4. Click nút **Start** để khởi động lại Apache

## Bước 6: Kiểm tra OPcache đã bật

Mở **PowerShell** hoặc **Command Prompt** và chạy:

```powershell
cd c:\xampp\htdocs\edtika
php -i | Select-String "opcache.enable"
```

Hoặc:

```powershell
php -r "echo extension_loaded('opcache') ? 'OPcache is ENABLED' : 'OPcache is DISABLED';"
```

Kết quả mong đợi:
```
OPcache is ENABLED
```

hoặc:

```
opcache.enable => On => On
```

## Bước 7: Test hiệu suất

1. Mở trình duyệt
2. Truy cập: `http://edtika.local`
3. Kiểm tra tốc độ tải trang (nên nhanh hơn rõ rệt)

## Troubleshooting

### Nếu không tìm thấy zend_extension=opcache

Thêm các dòng sau vào cuối file `php.ini`:

```ini
[opcache]
zend_extension=opcache
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
opcache.validate_timestamps=1
```

### Nếu Apache không start lại được

1. Kiểm tra lại syntax trong `php.ini`
2. Đảm bảo không có ký tự lạ
3. Kiểm tra logs: Click **Logs** trong XAMPP Control Panel

### Nếu OPcache vẫn chưa bật

1. Kiểm tra lại file `php.ini` đã lưu chưa
2. Restart lại máy (trong một số trường hợp)
3. Kiểm tra đúng file `php.ini` (XAMPP có thể có nhiều file)

## Lưu ý quan trọng

- **Development**: Set `opcache.validate_timestamps=1` để PHP check file changes
- **Production**: Set `opcache.validate_timestamps=0` để tối ưu tối đa
- **Memory**: Tăng `opcache.memory_consumption` nếu có nhiều file PHP
- **Revalidate**: `opcache.revalidate_freq=2` = check mỗi 2 giây

## Kết quả mong đợi

Với OPcache đã bật:
- ✅ Tốc độ tải trang tăng **50-70%**
- ✅ CPU usage giảm
- ✅ Response time giảm đáng kể
- ✅ Ít disk I/O hơn

# 🚀 Quick Start - Edtika Docker

## Chạy trong 3 bước:

### 1️⃣ Cấu hình hosts file

```powershell
# Chạy command này với quyền Administrator:
Add-Content -Path C:\Windows\System32\drivers\etc\hosts -Value "`n127.0.0.1       edtika.local"
```

### 2️⃣ Copy file .env cho Docker

```powershell
Copy-Item .env.docker .env -Force
```

### 3️⃣ Chạy setup script

```powershell
.\docker-setup.ps1
```

## ✅ Xong!

Truy cập: **http://edtika.local**

---

## 📊 Migrate Database từ XAMPP (Tùy chọn)

Nếu bạn đã có database trong XAMPP:

```powershell
# Cách 1: Export từ XAMPP và import luôn
.\migrate-database.ps1 -FromXAMPP

# Cách 2: Dùng file backup có sẵn
.\migrate-database.ps1 -BackupFile "database_backup.sql"
```

---

## 🛑 Dừng Containers

```powershell
docker-compose down
```

## 🔄 Khởi động lại

```powershell
docker-compose up -d
```

---

## 📖 Xem thêm

Chi tiết đầy đủ: [DOCKER-README.md](DOCKER-README.md)

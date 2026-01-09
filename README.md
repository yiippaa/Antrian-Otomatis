# Smart Queue Application 🏥

**Smart Queue Application** adalah aplikasi web berbasis Laravel yang **mensimulasikan sistem antrian loket digital** dengan menekankan **interaksi hardware secara virtual** melalui event listener keyboard dan mouse.  
Aplikasi ini memungkinkan pengunjung mengambil nomor antrian layaknya menekan tombol mesin antrian fisik, sementara petugas memanggil antrian melalui panel khusus.  
Nomor antrian yang dipanggil ditampilkan secara **realtime** dan dibacakan otomatis menggunakan teknologi **Text-to-Speech**, sehingga aplikasi ini berfungsi sebagai **media simulasi dan pembelajaran** untuk memahami konsep interaksi hardware dalam pengembangan web interaktif tanpa memerlukan perangkat tambahan.

---

## ✨ Fitur Utama

* Dashboard admin untuk mengelola poliklinik, pasien, dan antrian
* TV display untuk melihat antrian secara real-time
* Nomor antrian dibacakan otomatis (Text-to-Speech)
* Laporan antrian harian (queue reports)
* Login untuk admin
* Integrasi Livewire & Filament admin panel

---

## 🖥️ Perancangan User Interface

Aplikasi terdiri dari beberapa halaman utama:

* **Admin Panel**:  
  - Dashboard utama  
  - Halaman pasien  
  - Halaman poliklinik  
  - Halaman antrian  
  - Laporan antrian harian  

* **TV Display**:  
  - Menampilkan nomor antrian saat ini  
  - Update otomatis tanpa refresh halaman  
  - Nomor antrian dibacakan secara otomatis

---

## 🛠️ Teknologi yang Digunakan

* **Backend**: Laravel (PHP 8.5)  
* **Frontend**: Blade, Livewire, Filament  
* **Database**: SQLite / MySQL  
* **Tools**: Composer, Artisan  
* **Audio**: Text-to-Speech (TTS)

---

## ⚡ Cara Menjalankan Project

1. Clone repo:
```bash
git clone <URL_REPO_KAMU>
cd <NAMA_FOLDER_PROJECT>

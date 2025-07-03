# WP Snippet 🔧

**Kumpulan snippet WordPress praktis** yang digunakan untuk pengembangan tema, plugin, dan proyek berbasis WordPress lainnya.  
Disusun dan digunakan oleh [neon.web.id](https://neon.web.id) — siap pakai, ringan, dan efisien.

---

## 📁 Struktur Direktori

| Folder / File                         | Deskripsi Singkat                                      |
|--------------------------------------|--------------------------------------------------------|
| `generatepress-query-loop-related-post.php` | Tampilkan related post di GenerateBlocks berdasarkan kategori & tag |
| `class.base-post-type.php`          | Kelas dasar untuk mendaftarkan CPT secara OOP         |
| `WooWAButton.php`                   | Tombol WhatsApp otomatis di WooCommerce               |
| `SwitchUser.php`                    | Ganti user dengan mudah (admin only)                  |
| `fake-rating-post.php`              | Tambah rating palsu di post (untuk dummy/test)        |
| `breadcrumbs.php`                   | Breadcrumbs ringan tanpa plugin                       |
| `shortcode-related-inner-post.php`  | Shortcode untuk related post berdasarkan kategori     |
| ...                                  | dan banyak lagi                                        |

> Gunakan `CTRL+F` atau fitur pencarian GitHub untuk cari file yang kamu butuhkan.

---

## 🚀 Penggunaan

1. **Salin file yang dibutuhkan** ke dalam folder tema atau plugin kamu
2. Tambahkan via `require_once()` di `functions.php`
3. Beberapa snippet bisa langsung digunakan sebagai plugin tunggal

Contoh:
```php
require_once get_template_directory() . '/inc/snippets/generatepress-query-loop-related-post.php';
````

---

## 🧩 Cocok Untuk

* Developer WordPress yang butuh solusi cepat
* Tema berbasis GeneratePress, Astra, dan Block Theme
* Stack ringan, tanpa plugin tambahan
* Custom WP development (fungsi khusus, WooCommerce, dll)

---

## 🤝 Kontribusi

Pingin ikut nyumbang snippet atau perbaikan?

1. Fork repo ini
2. Tambahkan file kamu dengan nama jelas
3. Kirim pull request

Kami senang kolaborasi kecil yang bermanfaat besar.

---

## 🔗 Tautan Terkait

* Website: [https://neon.web.id](https://neon.web.id)
* Facebook: [facebook.com/neonwebid](https://www.facebook.com/neonwebid/)
* Artikel & Tutorial: [neon.web.id/blog](https://neon.web.id/blog)

---

## 📄 Lisensi

Kode di repo ini berlisensi **MIT License** — bebas digunakan, diubah, dan disebarluaskan, selama tetap mencantumkan kredit.

---

> Repo ini dikelola oleh `@wichaksono` untuk dokumentasi dan sharing snippet WordPress yang sering digunakan di proyek-proyek internal dan publik.


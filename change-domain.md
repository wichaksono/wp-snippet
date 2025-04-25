Berikut ini SQL untuk mengganti domain lama ke domain baru di WordPress secara langsung di database (misalnya via phpMyAdmin atau command line):

### Ganti Domain WordPress via SQL

#### Ganti di `wp_options`
```sql
UPDATE wp_options 
SET option_value = REPLACE(option_value, 'https://domainlama.com', 'https://domainbaru.com') 
WHERE option_name IN ('siteurl', 'home');
```

#### Ganti di `wp_posts` (konten, gambar, link internal)
```sql
UPDATE wp_posts 
SET post_content = REPLACE(post_content, 'https://domainlama.com', 'https://domainbaru.com');
```

#### Ganti di `wp_postmeta`
```sql
UPDATE wp_postmeta 
SET meta_value = REPLACE(meta_value, 'https://domainlama.com', 'https://domainbaru.com');
```

#### Ganti di `wp_usermeta`
```sql
UPDATE wp_usermeta 
SET meta_value = REPLACE(meta_value, 'https://domainlama.com', 'https://domainbaru.com');
```

#### Ganti di `wp_users`
```sql
UPDATE wp_users 
SET user_url = REPLACE(user_url, 'https://domainlama.com', 'https://domainbaru.com');
```

---

### Catatan
- **Backup database** dulu sebelum eksekusi.
- Ganti `https://domainlama.com` dan `https://domainbaru.com` sesuai kebutuhan.
- Jika prefix WordPress kamu bukan `wp_`, sesuaikan semua nama tabel.

Kalau kamu pakai plugin builder atau plugin lain, kadang ada juga data serialized. Untuk itu sebaiknya pakai plugin seperti **Better Search Replace** atau tools seperti **WP-CLI search-replace** agar data tidak korup. Perlu bantu contoh pakai plugin?

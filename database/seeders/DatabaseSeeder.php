<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('Notifications')->truncate();
        DB::table('Packing_Items')->truncate();
        DB::table('Packing_Lists')->truncate();
        DB::table('Calendar_Entries')->truncate();
        DB::table('AI_Recommendations')->truncate();
        DB::table('Outfit_Items')->truncate();
        DB::table('Outfits')->truncate();
        DB::table('Clothing_Items')->truncate();
        DB::table('Categories')->truncate();
        DB::table('Users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $defaultPassword = Hash::make('password123');

        // 1. Users (5 Nama Panggilan Tim + 3 Nama Random dengan format @email.com)
        DB::table('Users')->insert([
            [
                'id' => 1,
                'nama' => 'Aidan',
                'email' => 'aidan@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => now(),
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/aidan.png',
                'lokasi_default' => 'Jakarta, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'nama' => 'Debra',
                'email' => 'debra@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => now(),
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/debra.png',
                'lokasi_default' => 'Depok, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'nama' => 'Yanuar',
                'email' => 'yanuar@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => null,
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/yanuar.png',
                'lokasi_default' => 'Bogor, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'nama' => 'Azka',
                'email' => 'azka@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => null,
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/azka.png',
                'lokasi_default' => 'Bandung, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'nama' => 'Dhyo',
                'email' => 'dhyo@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => null,
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/dhyo.png',
                'lokasi_default' => 'Tangerang, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 6,
                'nama' => 'Raka',
                'email' => 'raka@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => null,
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/raka.png',
                'lokasi_default' => 'Surabaya, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 7,
                'nama' => 'Nadya',
                'email' => 'nadya@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => null,
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/nadya.png',
                'lokasi_default' => 'Yogyakarta, Indonesia',
                'created_at' => now(),
            ],
            [
                'id' => 8,
                'nama' => 'Kevin',
                'email' => 'kevin@email.com',
                'password_hash' => $defaultPassword,
                'password_updated_at' => null,
                'avatar_url' => 'https://storage.nothingtowear.app/avatars/kevin.png',
                'lokasi_default' => 'Semarang, Indonesia',
                'created_at' => now(),
            ],
        ]);

        // 2. Categories
        DB::table('Categories')->insert([
            ['id' => 1, 'nama_kategori' => 'Tops', 'ikon_url' => 'https://storage.nothingtowear.app/icons/tops.svg', 'urutan_tampil' => 1],
            ['id' => 2, 'nama_kategori' => 'Bottoms', 'ikon_url' => 'https://storage.nothingtowear.app/icons/bottoms.svg', 'urutan_tampil' => 2],
            ['id' => 3, 'nama_kategori' => 'Outerwear', 'ikon_url' => 'https://storage.nothingtowear.app/icons/outerwear.svg', 'urutan_tampil' => 3],
            ['id' => 4, 'nama_kategori' => 'Footwear', 'ikon_url' => 'https://storage.nothingtowear.app/icons/footwear.svg', 'urutan_tampil' => 4],
            ['id' => 5, 'nama_kategori' => 'Accessories', 'ikon_url' => 'https://storage.nothingtowear.app/icons/accessories.svg', 'urutan_tampil' => 5],
        ]);

        // 3. Clothing_Items
        DB::table('Clothing_Items')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'category_id' => 3,
                'nama_item' => 'Cashmere Trench Coat',
                'warna' => 'Beige',
                'brand' => 'Lemaire',
                'ukuran' => 'L',
                'style_occasion' => 'Smart Casual',
                'tag' => 'cashmere,trench,wind resistant,autumn',
                'url_gambar' => 'https://storage.nothingtowear.app/items/cashmere_trench.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'category_id' => 1,
                'nama_item' => 'Ribbed Wool Turtleneck',
                'warna' => 'Brown',
                'brand' => 'Uniqlo U',
                'ukuran' => 'L',
                'style_occasion' => 'Smart Casual',
                'tag' => 'knitwear,wool,turtleneck,warm',
                'url_gambar' => 'https://storage.nothingtowear.app/items/ribbed_wool_turtleneck.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => 1,
                'category_id' => 2,
                'nama_item' => 'Pleated Wide Trousers',
                'warna' => 'Charcoal Grey',
                'brand' => 'Zara',
                'ukuran' => '32',
                'style_occasion' => 'Formal & Evening',
                'tag' => 'trousers,wide-leg,pleated',
                'url_gambar' => 'https://storage.nothingtowear.app/items/pleated_trousers.png',
                'is_favorite' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'user_id' => 1,
                'category_id' => 4,
                'nama_item' => 'Lemaire Leather Boots',
                'warna' => 'Black',
                'brand' => 'Lemaire',
                'ukuran' => '42',
                'style_occasion' => 'Smart Casual',
                'tag' => 'boots,leather,waterproof',
                'url_gambar' => 'https://storage.nothingtowear.app/items/lemaire_boots.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'user_id' => 1,
                'category_id' => 3,
                'nama_item' => 'Double Cashmere Coat',
                'warna' => 'Dark Brown',
                'brand' => 'COS',
                'ukuran' => 'L',
                'style_occasion' => 'Formal & Evening',
                'tag' => 'cashmere,coat,winter',
                'url_gambar' => 'https://storage.nothingtowear.app/items/double_cashmere_coat.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'user_id' => 1,
                'category_id' => 1,
                'nama_item' => 'Heavyweight Boxy Tee',
                'warna' => 'Black',
                'brand' => 'Essentials',
                'ukuran' => 'L',
                'style_occasion' => 'Casual',
                'tag' => 't-shirt,cotton,summer,streetwear',
                'url_gambar' => 'https://storage.nothingtowear.app/items/black_boxy_tee.png',
                'is_favorite' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'user_id' => 1,
                'category_id' => 2,
                'nama_item' => 'Light Wash Straight Jeans',
                'warna' => 'Light Blue',
                'brand' => 'Levi\'s',
                'ukuran' => '32',
                'style_occasion' => 'Casual',
                'tag' => 'denim,jeans,summer,casual',
                'url_gambar' => 'https://storage.nothingtowear.app/items/light_wash_jeans.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. Outfits
        DB::table('Outfits')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'nama' => 'Autumn Smart Casual',
                'mood_event' => 'Coffee Outing & Overcast Day',
                'metode_pembuatan' => 'Full AI Builder',
                'preview_url' => 'https://storage.nothingtowear.app/outfits/autumn_smart_casual.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'nama' => 'Summer Casual Hangout',
                'mood_event' => 'Weekend City Stroll',
                'metode_pembuatan' => 'Manual',
                'preview_url' => 'https://storage.nothingtowear.app/outfits/summer_casual.png',
                'is_favorite' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Outfit_Items
        DB::table('Outfit_Items')->insert([
            ['outfit_id' => 1, 'item_id' => 1, 'pos_x' => 32.5, 'pos_y' => 45.0, 'z_index' => 3, 'scale' => 1.10, 'is_key_item' => 1, 'updated_at' => now()],
            ['outfit_id' => 1, 'item_id' => 2, 'pos_x' => 185.0, 'pos_y' => 40.0, 'z_index' => 2, 'scale' => 1.00, 'is_key_item' => 0, 'updated_at' => now()],
            ['outfit_id' => 1, 'item_id' => 3, 'pos_x' => 120.0, 'pos_y' => 160.0, 'z_index' => 1, 'scale' => 1.05, 'is_key_item' => 0, 'updated_at' => now()],
            ['outfit_id' => 1, 'item_id' => 4, 'pos_x' => 230.0, 'pos_y' => 210.0, 'z_index' => 4, 'scale' => 0.95, 'is_key_item' => 0, 'updated_at' => now()],
            ['outfit_id' => 2, 'item_id' => 6, 'pos_x' => 80.0, 'pos_y' => 35.0, 'z_index' => 2, 'scale' => 1.00, 'is_key_item' => 0, 'updated_at' => now()],
            ['outfit_id' => 2, 'item_id' => 7, 'pos_x' => 150.0, 'pos_y' => 140.0, 'z_index' => 1, 'scale' => 1.05, 'is_key_item' => 0, 'updated_at' => now()],
        ]);

        // 6. AI_Recommendations
        DB::table('AI_Recommendations')->insert([
            'id' => 1,
            'user_id' => 1,
            'outfit_id' => 1,
            'judul_rekomendasi' => 'Autumn Smart Casual',
            'deskripsi_konteks' => 'For overcast skies and a pumpkin spice latte outing.',
            'tag_cuaca' => 'wind resistant',
            'is_saved' => 1,
            'tanggal_rekomendasi' => now()->toDateString(),
        ]);

        // 7. Calendar_Entries
        $calendarId = DB::table('Calendar_Entries')->insertGetId([
            'user_id' => 1,
            'outfit_id' => 1,
            'tanggal' => now()->addDay()->toDateString(),
            'waktu' => '09:00:00',
            'lokasi' => 'Depok, Jawa Barat',
            'event' => 'Sprint Review & API Integration Demo',
            'kondisi_cuaca' => 'Berawan / Overcast',
            'suhu' => 26.5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Packing_Lists & Packing_Items
        $packingId = DB::table('Packing_Lists')->insertGetId([
            'user_id' => 1,
            'nama_perjalanan' => 'Workcation & Tech Conference',
            'destinasi' => 'Bandung, Jawa Barat',
            'tgl_mulai' => now()->addDays(7)->toDateString(),
            'tgl_selesai' => now()->addDays(9)->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('Packing_Items')->insert([
            ['packing_id' => $packingId, 'item_id' => 1, 'jumlah' => 1, 'is_packed' => 1, 'updated_at' => now()],
            ['packing_id' => $packingId, 'item_id' => 2, 'jumlah' => 2, 'is_packed' => 1, 'updated_at' => now()],
            ['packing_id' => $packingId, 'item_id' => 3, 'jumlah' => 1, 'is_packed' => 0, 'updated_at' => now()],
        ]);

        // 9. Notifications
        DB::table('Notifications')->insert([
            'user_id' => 1,
            'calendar_id' => $calendarId,
            'judul' => 'OOTD Reminder Besok Pagi',
            'pesan' => 'Jangan lupa siapkan outfit "Autumn Smart Casual" untuk agenda besok pukul 09:00.',
            'tipe_notifikasi' => 'OOTD Reminder',
            'is_read' => 0,
            'created_at' => now(),
        ]);
    }
}
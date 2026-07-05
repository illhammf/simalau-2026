<?php

namespace Database\Seeders;

use App\Models\PengaturanSistem;
use Illuminate\Database\Seeder;

class PengaturanSistemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengaturanSistem::updateOrCreate(
            ['nama_laundry' => 'LaundryKita'],
            [
                'alamat' => 'Jl. Melati No. 10, Bandung',
                'nomor_whatsapp' => '081234567890',
                'email' => 'admin@laundrykita.test',
                'jam_buka' => '08:00:00',
                'jam_tutup' => '20:00:00',
                'deskripsi' => 'LaundryKita adalah layanan laundry berbasis web yang melayani laundry kiloan, laundry satuan, cuci sepatu, cuci selimut, dan cuci karpet.',
                'catatan_nota' => 'Terima kasih telah menggunakan layanan LaundryKita. Simpan nota ini sebagai bukti transaksi.',
                'logo' => null,
                'latitude' => null,
                'longitude' => null,
                'status_sistem' => 'aktif',
            ]
        );
    }
}
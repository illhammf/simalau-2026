<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    //
}
<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\KategoriLayanan;
use App\Models\LayananLaundry;
use App\Models\PengaturanSistem;

class LayananController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSistem::query()
            ->where('status_sistem', 'aktif')
            ->first();

        $kategoriLayanan = KategoriLayanan::query()
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get();

        $layananLaundries = LayananLaundry::query()
            ->with('kategoriLayanan')
            ->where('status', 'aktif')
            ->when(request('kategori'), function ($query, $kategori) {
                $query->whereHas('kategoriLayanan', function ($query) use ($kategori) {
                    $query->where('slug', $kategori);
                });
            })
            ->when(request('tipe'), function ($query, $tipe) {
                $query->where('tipe_layanan', $tipe);
            })
            ->orderBy('nama_layanan')
            ->paginate(9)
            ->withQueryString();

        return view('front.layanan.index', [
            'pengaturan' => $pengaturan,
            'kategoriLayanan' => $kategoriLayanan,
            'layananLaundries' => $layananLaundries,
        ]);
    }

    public function show(string $slug)
    {
        $pengaturan = PengaturanSistem::query()
            ->where('status_sistem', 'aktif')
            ->first();

        $layanan = LayananLaundry::query()
            ->with('kategoriLayanan')
            ->where('status', 'aktif')
            ->where('slug', $slug)
            ->firstOrFail();

        $layananTerkait = LayananLaundry::query()
            ->with('kategoriLayanan')
            ->where('status', 'aktif')
            ->where('id', '!=', $layanan->id)
            ->where('kategori_layanan_id', $layanan->kategori_layanan_id)
            ->limit(3)
            ->get();

        return view('front.layanan.show', [
            'pengaturan' => $pengaturan,
            'layanan' => $layanan,
            'layananTerkait' => $layananTerkait,
        ]);
    }
}
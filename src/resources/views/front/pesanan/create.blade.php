@extends('front.layouts.app', [
    'title' => 'Buat Pesanan - ' . ($pengaturan->nama_laundry ?? 'LaundryKita'),
    'pengaturan' => $pengaturan,
])

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Buat Pesanan</p>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900">
                Buat pesanan laundry baru.
            </h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">
                Pilih layanan laundry, isi jumlah cucian, lalu pantau status pesanan dari halaman Pesanan Saya.
            </p>
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('front.pesanan.store') }}" class="space-y-6">
            @csrf

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Data Pelanggan</h2>
                <p class="mt-2 text-sm text-slate-500">Data ini digunakan untuk identitas pesanan dan kontak pengambilan.</p>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="nama_lengkap" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input
                            type="text"
                            id="nama_lengkap"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap', $pelanggan->nama_lengkap ?? auth()->user()->name) }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required
                        >
                    </div>

                    <div>
                        <label for="nomor_whatsapp" class="mb-2 block text-sm font-semibold text-slate-700">Nomor WhatsApp</label>
                        <input
                            type="text"
                            id="nomor_whatsapp"
                            name="nomor_whatsapp"
                            value="{{ old('nomor_whatsapp', $pelanggan->nomor_whatsapp ?? '') }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat pelanggan"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Detail Cucian</h2>
                <p class="mt-2 text-sm text-slate-500">Pilih layanan dan isi jumlah cucian sesuai tipe layanan.</p>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label for="layanan_laundry_id" class="mb-2 block text-sm font-semibold text-slate-700">Layanan Laundry</label>
                        <select
                            id="layanan_laundry_id"
                            name="layanan_laundry_id"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required
                        >
                            <option value="">Pilih layanan</option>
                            @foreach ($layananLaundries as $layanan)
                                <option
                                    value="{{ $layanan->id }}"
                                    data-tipe="{{ $layanan->tipe_layanan }}"
                                    data-tarif="{{ $layanan->tarif }}"
                                    data-satuan="{{ $layanan->satuan_hitung }}"
                                    data-estimasi="{{ $layanan->estimasi_hari }}"
                                    @selected(old('layanan_laundry_id') == $layanan->id)
                                >
                                    {{ $layanan->nama_layanan }}
                                    - Rp {{ number_format($layanan->tarif, 0, ',', '.') }}
                                    / {{ $layanan->satuan_hitung }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="field-berat">
                        <label for="berat" class="mb-2 block text-sm font-semibold text-slate-700">Berat Cucian</label>
                        <input
                            type="number"
                            step="0.1"
                            min="0.1"
                            id="berat"
                            name="berat"
                            value="{{ old('berat') }}"
                            placeholder="Contoh: 3"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        <p class="mt-2 text-xs text-slate-500">Diisi untuk layanan kiloan.</p>
                    </div>

                    <div id="field-jumlah-item">
                        <label for="jumlah_item" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Item</label>
                        <input
                            type="number"
                            min="1"
                            id="jumlah_item"
                            name="jumlah_item"
                            value="{{ old('jumlah_item') }}"
                            placeholder="Contoh: 2"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                        <p class="mt-2 text-xs text-slate-500">Diisi untuk layanan satuan.</p>
                    </div>

                    <div class="md:col-span-2 rounded-2xl bg-blue-50 p-5">
                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Tarif</p>
                                <p id="preview-tarif" class="mt-1 text-lg font-bold text-slate-900">Rp 0</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Estimasi</p>
                                <p id="preview-estimasi" class="mt-1 text-lg font-bold text-slate-900">-</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Total Perkiraan</p>
                                <p id="preview-total" class="mt-1 text-lg font-bold text-slate-900">Rp 0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Penyerahan Cucian</h2>
                <p class="mt-2 text-sm text-slate-500">Pilih apakah cucian diantar sendiri atau dijemput oleh pihak laundry.</p>

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="metode_penyerahan" class="mb-2 block text-sm font-semibold text-slate-700">Metode Penyerahan</label>
                        <select
                            id="metode_penyerahan"
                            name="metode_penyerahan"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required
                        >
                            <option value="antar_sendiri" @selected(old('metode_penyerahan') === 'antar_sendiri')>Antar sendiri ke outlet</option>
                            <option value="jemput" @selected(old('metode_penyerahan') === 'jemput')>Minta dijemput</option>
                        </select>
                    </div>

                    <div id="field-alamat-penjemputan">
                        <label for="alamat_penjemputan" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Penjemputan</label>
                        <input
                            type="text"
                            id="alamat_penjemputan"
                            name="alamat_penjemputan"
                            value="{{ old('alamat_penjemputan') }}"
                            placeholder="Isi jika memilih jemput"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan_pelanggan" class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                        <textarea
                            id="catatan_pelanggan"
                            name="catatan_pelanggan"
                            rows="3"
                            placeholder="Contoh: Tolong pisahkan pakaian putih dan berwarna."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        >{{ old('catatan_pelanggan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('front.layanan.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:border-blue-600 hover:text-blue-600">
                    Batal
                </a>

                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Simpan Pesanan
                </button>
            </div>
        </form>
    </div>
</section>

<script>
    const layananSelect = document.getElementById('layanan_laundry_id');
    const beratInput = document.getElementById('berat');
    const jumlahItemInput = document.getElementById('jumlah_item');
    const fieldBerat = document.getElementById('field-berat');
    const fieldJumlahItem = document.getElementById('field-jumlah-item');

    const metodePenyerahan = document.getElementById('metode_penyerahan');
    const fieldAlamatPenjemputan = document.getElementById('field-alamat-penjemputan');

    const previewTarif = document.getElementById('preview-tarif');
    const previewEstimasi = document.getElementById('preview-estimasi');
    const previewTotal = document.getElementById('preview-total');

    function rupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(value);
    }

    function updateLayananPreview() {
        const selected = layananSelect.options[layananSelect.selectedIndex];

        const tipe = selected.dataset.tipe;
        const tarif = Number(selected.dataset.tarif || 0);
        const satuan = selected.dataset.satuan || '-';
        const estimasi = selected.dataset.estimasi || '-';

        fieldBerat.style.display = tipe === 'kiloan' ? 'block' : 'none';
        fieldJumlahItem.style.display = tipe === 'satuan' ? 'block' : 'none';

        const berat = Number(beratInput.value || 0);
        const jumlahItem = Number(jumlahItemInput.value || 0);

        const jumlah = tipe === 'kiloan' ? berat : jumlahItem;
        const total = tarif * jumlah;

        previewTarif.textContent = `${rupiah(tarif)} / ${satuan}`;
        previewEstimasi.textContent = estimasi !== '-' ? `${estimasi} hari` : '-';
        previewTotal.textContent = rupiah(total);
    }

    function updateMetodePenyerahan() {
        fieldAlamatPenjemputan.style.display = metodePenyerahan.value === 'jemput' ? 'block' : 'none';
    }

    layananSelect.addEventListener('change', updateLayananPreview);
    beratInput.addEventListener('input', updateLayananPreview);
    jumlahItemInput.addEventListener('input', updateLayananPreview);
    metodePenyerahan.addEventListener('change', updateMetodePenyerahan);

    updateLayananPreview();
    updateMetodePenyerahan();
</script>
@endsection
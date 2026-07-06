@extends('front.layouts.app', [
    'title' => 'Daftar Layanan - ' . ($pengaturan->nama_laundry ?? 'LaundryKita'),
    'pengaturan' => $pengaturan,
])

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Daftar Layanan</p>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900">
                Pilih layanan laundry sesuai kebutuhan.
            </h1>
            <p class="mt-5 text-lg leading-8 text-slate-600">
                Tersedia layanan kiloan dan satuan. Pelanggan dapat melihat tarif, estimasi pengerjaan, dan satuan hitung sebelum membuat pesanan.
            </p>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('front.layanan.index') }}" class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
                <select id="kategori" name="kategori" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriLayanan as $kategori)
                        <option value="{{ $kategori->slug }}" @selected(request('kategori') === $kategori->slug)>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tipe" class="mb-2 block text-sm font-semibold text-slate-700">Tipe Layanan</label>
                <select id="tipe" name="tipe" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Tipe</option>
                    <option value="kiloan" @selected(request('tipe') === 'kiloan')>Kiloan</option>
                    <option value="satuan" @selected(request('tipe') === 'satuan')>Satuan</option>
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Terapkan Filter
                </button>

                <a href="{{ route('front.layanan.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:border-blue-600 hover:text-blue-600">
                    Reset
                </a>
            </div>
        </form>
    </div>
</section>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($layananLaundries as $layanan)
                <a href="{{ route('front.layanan.show', $layanan->slug) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-blue-600">
                                {{ $layanan->kategoriLayanan->nama_kategori ?? '-' }}
                            </p>

                            <h2 class="mt-2 text-xl font-bold text-slate-900 group-hover:text-blue-700">
                                {{ $layanan->nama_layanan }}
                            </h2>
                        </div>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            {{ ucfirst($layanan->tipe_layanan) }}
                        </span>
                    </div>

                    <p class="mt-4 line-clamp-3 text-sm leading-6 text-slate-600">
                        {{ $layanan->deskripsi ?? 'Layanan laundry tersedia untuk pelanggan.' }}
                    </p>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tarif</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">
                                Rp {{ number_format($layanan->tarif, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-500">per {{ $layanan->satuan_hitung }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estimasi</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">
                                {{ $layanan->estimasi_hari }} hari
                            </p>
                            <p class="text-xs text-slate-500">proses laundry</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-10 text-center">
                    <h2 class="text-lg font-bold text-slate-900">Layanan tidak ditemukan</h2>
                    <p class="mt-2 text-sm text-slate-500">Coba ubah filter kategori atau tipe layanan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $layananLaundries->links() }}
        </div>
    </div>
</section>
@endsection
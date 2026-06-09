@extends('layouts.frontend')

@section('content')
<article class="max-w-[800px] mx-auto px-6 lg:px-0 py-10">

    {{-- Tombol Back: hanya muncul di mobile --}}
    {{-- Posisi: paling atas, sejajar kiri, sebelum semua konten artikel --}}
    <div id="back-btn-wrapper" class="md:hidden flex items-center mb-5">
        <button onclick="handleBack()"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 px-3 py-1.5 rounded-full transition-all duration-200">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            <span id="btn-back-label">Kembali</span>
        </button>
    </div>

    <div class="flex items-center gap-3 text-xs font-semibold mb-4 uppercase tracking-wider text-gray-500">
        <span class="text-indigo-600 font-bold">{{ $berita->kategori->nama_kategori ?? 'Umum' }}</span>
    </div>

    <nav class="flex text-xs text-gray-400 gap-2 mb-6 font-medium">
        <a href="/" class="hover:text-gray-900 transition">Beranda</a>
        <span>&gt;</span>
        <a href="/kategori/{{ $berita->kategori->slug_kategori ?? '' }}" class="hover:text-gray-900 transition">
            {{ $berita->kategori->nama_kategori ?? 'Kategori' }}
        </a>
        <span>&gt;</span>
        <span class="text-gray-600 line-clamp-1">{{ $berita->judul_berita }}</span>
    </nav>

    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-[1.2] tracking-tight mb-8">
        {{ $berita->judul_berita }}
    </h1>

    <!-- Cari bagian ini di detail-berita.blade.php -->
<div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-8">
        
        <div class="flex items-center gap-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($berita->penulis ?? 'Admin') }}&background=4F46E5&color=fff" class="w-10 h-10 rounded-full object-cover shadow-sm">
            <div>
                <p class="text-sm font-bold text-gray-900">{{ $berita->penulis ?? 'Admin' }}</p>
                <p class="text-xs text-gray-400">{{ date('d M Y', strtotime($berita->created_at)) }}</p>
            </div>
        </div>

        <div class="flex items-center gap-4 text-xs font-semibold text-gray-400">
            <span class="flex items-center gap-1.5 bg-gray-50 px-3 py-1.5 rounded-full">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span id="views-counter">{{ number_format($berita->views ?? 0, 0, ',', '.') }} Views</span>
            </span>
        </div>

    </div>

    <div class="w-full h-[400px] rounded-3xl overflow-hidden shadow-sm mb-4">
        <img src="{{ asset('assets/images/berita/'.$berita->gambar_thumbnail) }}" class="w-full h-full object-cover" alt="{{ $berita->judul_berita }}">
    </div>
    
    <p class="text-xs text-center text-gray-400 italic mb-10 leading-relaxed max-w-2xl mx-auto border-b border-gray-50 pb-4">
        Dokumentasi resmi terkait {{ $berita->judul_berita }}. (Foto: Dok. Pemkot)
    </p>

    <div class="prose prose-indigo max-w-none text-gray-700 text-base leading-[1.75] font-normal space-y-6">
        {!! $berita->isi_berita !!}
    </div>

</article>

<script>
    const viewsEl   = document.getElementById('views-counter');
    const urlIncrement = "{{ route('berita.incrementView', $berita->id_berita) }}";
    const urlGetViews  = "{{ route('berita.getViews', $berita->id_berita) }}";
    const csrfToken    = '{{ csrf_token() }}';

    /**
     * PERTAMA KALI HALAMAN DIBUKA:
     * Increment views via POST AJAX — tanpa reload.
     * Angka di span diupdate dari response JSON.
     */
    document.addEventListener('DOMContentLoaded', function () {
        fetch(urlIncrement, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success && viewsEl) {
                viewsEl.textContent = data.views.toLocaleString('id-ID') + ' Views';
            }
        })
        .catch(function (err) {
            console.warn('Increment views gagal:', err);
        });
    });

    /**
     * SAAT NAVIGASI BACK (halaman dari cache browser):
     * JANGAN increment lagi — cukup fetch angka views terkini via GET
     * dan update span tanpa reload halaman sama sekali.
     *
     * event.persisted = true artinya halaman diambil dari bfcache (back-forward cache)
     */
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            fetch(urlGetViews, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success && viewsEl) {
                    viewsEl.textContent = data.views.toLocaleString('id-ID') + ' Views';
                }
            })
            .catch(function (err) {
                console.warn('Fetch views gagal:', err);
            });
        }
    });
</script>

<script>
    /**
     * Tombol Back — Logika Dinamis
     *
     * Prioritas tujuan navigasi:
     * 1. Jika referer adalah halaman Home (/) → kembali ke Home + label "Beranda"
     * 2. Jika ada history browser (dalam satu sesi) → history.back()
     * 3. Fallback: kembali ke Home
     *
     * Label tombol disesuaikan agar user tahu kemana mereka kembali.
     */
    (function () {
        const label  = document.getElementById('btn-back-label');
        const referer = document.referrer;
        const origin  = window.location.origin;

        // Cek apakah referer adalah halaman Home
        const isFromHome = referer === origin + '/' || referer === origin;
        // Cek apakah referer masih dari domain yang sama (satu sesi navigasi)
        const isFromSameSite = referer && referer.startsWith(origin);

        if (isFromHome) {
            if (label) label.textContent = 'Beranda';
        } else if (isFromSameSite) {
            // Coba deteksi nama halaman dari URL referer untuk label yang informatif
            try {
                const refPath = new URL(referer).pathname;
                if (refPath.startsWith('/kategori')) {
                    const parts = refPath.split('/').filter(Boolean);
                    const namaKategori = parts[1] ? decodeURIComponent(parts[1]).replace(/-/g, ' ') : 'Kategori';
                    if (label) label.textContent = namaKategori.charAt(0).toUpperCase() + namaKategori.slice(1);
                } else {
                    if (label) label.textContent = 'Kembali';
                }
            } catch (e) {
                if (label) label.textContent = 'Kembali';
            }
        } else {
            // Tidak ada referer (akses langsung / link eksternal) — sembunyikan tombol
            const wrapper = document.getElementById('back-btn-wrapper');
            if (wrapper) wrapper.classList.add('hidden');
        }
    })();

    function handleBack() {
        const referer = document.referrer;
        const origin  = window.location.origin;
        const isFromSameSite = referer && referer.startsWith(origin);

        if (isFromSameSite) {
            // Gunakan history.back() agar state halaman (scroll position, dll) terjaga
            history.back();
        } else {
            // Fallback ke Home jika tidak ada history yang valid
            window.location.href = '/';
        }
    }
</script>
@endsection
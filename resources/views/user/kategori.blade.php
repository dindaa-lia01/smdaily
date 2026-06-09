@extends('layouts.frontend')

@section('content')
<div class="w-full bg-slate-50 min-h-[600px] py-16 flex items-center justify-center">
    <div class="max-w-6xl mx-auto px-6 w-full text-center">
        
        <h1 class="text-4xl font-extrabold text-slate-900 mb-2 tracking-tight">
            Kategori: {{ $kategori->nama_kategori }}
        </h1>
        <p class="text-gray-500 mb-12">Arsip publikasi informasi berkala portal Smdaily.</p>

        {{-- JIKA SUDAH ADA BERITANYA (DIPAGINASI MAKSIMAL 6 DATA) --}}
        @if($berita->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                @foreach($berita as $b)
                <a href="{{ route('berita.detail', $b->slug_berita) }}" class="block group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                    <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                        <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Thumbnail Berita">
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-indigo-600 transition mb-3">
                            {{ $b->judul_berita }}
                        </h3>
                        <div class="flex justify-between items-center text-xs text-gray-400 font-medium pt-2 border-t border-gray-50">
                            <span>{{ date('d M Y', strtotime($b->created_at)) }}</span>
                            <span id="views-card-{{ $b->id_berita }}">
                                <i class="far fa-eye mr-1"></i>
                                <span class="views-text">{{ number_format($b->views ?? 0, 0, ',', '.') }}</span>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <div class="mt-12 flex justify-center">
                <div class="flex items-center space-x-1">
                    @if ($berita->onFirstPage())
                        <span class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed select-none">&lt;</span>
                    @else
                        <a href="{{ $berita->previousPageUrl() }}" class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">&lt;</a>
                    @endif

                    @foreach ($berita->getUrlRange(1, $berita->lastPage()) as $page => $url)
                        @if ($page == 1 || $page == $berita->lastPage() || abs($page - $berita->currentPage()) < 2)
                            @if ($page == $berita->currentPage())
                                <span class="h-9 w-9 text-xs flex items-center justify-center rounded-lg bg-blue-600 text-white font-bold shadow-sm select-none">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="h-9 w-9 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">{{ $page }}</a>
                            @endif
                        @elseif ($page == 2 || $page == $berita->lastPage() - 1)
                            @if(!isset($dots))
                                <span class="h-9 w-9 text-xs flex items-center justify-center text-gray-400 select-none">...</span>
                                @php $dots = true; @endphp
                            @endif
                        @endif
                    @endforeach

                    @if ($berita->hasMorePages())
                        <a href="{{ $berita->nextPageUrl() }}" class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">&gt;</a>
                    @else
                        <span class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed select-none">&gt;</span>
                    @endif
                </div>
            </div>

        {{-- JIKA BELUM ADA BERITANYA (KATEGORI AKTIF BARU YG MASIH KOSONG) -> AUTOMATIS POP UP COMING SOON --}}
        @else
            <div class="max-w-md mx-auto bg-white border border-gray-200 rounded-3xl p-8 shadow-xl animate-in fade-in zoom-in duration-300">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-inner">
                    <i class="fas fa-hourglass-half animate-spin [animation-duration:4s]"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Coming Soon!</h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">
                    Saat ini kontributor redaksi kami sedang menyusun artikel berita resmi untuk kategori <span class="font-bold text-indigo-600">"{{ $kategori->nama_kategori }}"</span>. Tetap pantau halaman ini secara berkala ya!
                </p>
                <a href="/" class="inline-flex items-center gap-2 bg-indigo-950 text-white font-bold text-xs px-6 py-3 rounded-full hover:bg-indigo-900 transition shadow-md">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        @endif

    </div>
</div>

<script>
/**
 * SINKRONISASI VIEWS CARD — kategori.blade.php
 * Fetch views terbaru saat user navigasi back (bfcache), tanpa increment.
 */
window.addEventListener('pageshow', function (event) {
    if (!event.persisted) return;

    document.querySelectorAll('[id^="views-card-"]').forEach(function (span) {
        const idBerita = span.id.replace('views-card-', '');
        fetch('/berita/' + idBerita + '/get-views', {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.success) {
                const textEl = span.querySelector('.views-text');
                if (textEl) textEl.textContent = data.views.toLocaleString('id-ID');
            }
        })
        .catch(function () {});
    });
});
</script>
@endsection
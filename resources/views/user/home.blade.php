@extends('layouts.frontend')

@section('content')

<!-- 2. SLIDER 3D -->
<div class="w-full bg-indigo-950 relative overflow-hidden py-10 border-b border-indigo-900/50">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
    <div class="max-w-[1920px] mx-auto relative z-10 flex items-center justify-center min-h-[260px] md:min-h-[420px] lg:min-h-[480px] px-4 overflow-hidden">
        <div id="carousel-3d-track" class="flex items-center justify-center w-full h-full relative">
            @foreach($sliderBerita as $index => $sb)
            <a href="{{ route('berita.detail', $sb->slug_berita) }}" class="carousel-3d-item absolute w-[92%] md:w-[65%] lg:w-[55%] h-[220px] md:h-[360px] lg:h-[420px] rounded-2xl md:rounded-3xl overflow-hidden shadow-2xl transition-all duration-700 ease-in-out select-none cursor-pointer" data-index="{{ $index }}">
                <div class="w-full h-full relative">
                    <img src="{{ asset('assets/images/berita/'.$sb->gambar_thumbnail) }}" alt="{{ $sb->judul_berita }}" class="w-full h-full object-cover" draggable="false">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent"></div>
                </div>
                <span class="absolute top-3 left-3 md:top-6 md:left-6 bg-emerald-500 text-gray-900 text-[9px] md:text-[11px] font-bold uppercase tracking-wider px-2.5 py-0.5 md:px-3.5 md:py-1.5 rounded-sm shadow-lg z-30">
                    {{ $sb->kategori->nama_kategori ?? 'Umum' }}
                </span>
                <div class="carousel-caption absolute bottom-0 left-0 w-full p-4 md:p-8 lg:p-12 z-20 text-left transition-opacity duration-500">
                    <h1 class="text-sm md:text-2xl lg:text-3xl font-bold text-white leading-snug tracking-tight drop-shadow-md line-clamp-2">
                        {{ $sb->judul_berita }}
                    </h1>
                </div>
            </a>
            @endforeach
        </div>
        <button id="prev-3d" class="absolute left-2 md:left-10 lg:left-14 z-40 bg-black/30 hover:bg-black/60 text-white p-2 md:p-3 rounded-full backdrop-blur-sm transition duration-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="next-3d" class="absolute right-2 md:right-10 lg:right-14 z-40 bg-black/30 hover:bg-black/60 text-white p-2 md:p-3 rounded-full backdrop-blur-sm transition duration-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
</div>

<!-- 3. KONTEN BERITA -->
<div class="max-w-[1536px] mx-auto px-6 lg:px-16 pt-10 pb-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <div class="lg:col-span-8">
            <h2 class="text-3xl font-bold text-gray-900 border-b-[3px] border-gray-900 pb-3 mb-6">Terkini</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                @foreach($berita as $b)
                    <a href="{{ route('berita.detail', $b->slug_berita) }}" class="block group">
                        <article class="flex flex-col">

                            {{-- Gambar: bebas tanpa card border, dengan badge kategori pojok kiri atas --}}
                            <div class="relative w-full aspect-[16/10] overflow-hidden rounded-2xl flex-shrink-0">
                                <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                     alt="{{ $b->judul_berita }}">
                                {{-- Badge kategori di atas gambar --}}
                                <span class="absolute top-3.5 left-3.5 bg-white text-gray-800 text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-md shadow-sm z-10">
                                    {{ $b->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                            </div>

                            {{-- Konten teks di bawah gambar, tanpa background card --}}
                            <div class="pt-4 flex flex-col gap-1.5">
                                {{-- Tanggal & Views --}}
                                <div class="flex items-center justify-between gap-2">
                                    <p class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($b->created_at)->translatedFormat('d M Y') }}
                                    </p>
                                    <span class="flex items-center gap-1 text-xs text-gray-400 font-medium flex-shrink-0"
                                          id="views-card-{{ $b->id_berita }}">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="views-text">{{ number_format($b->views ?? 0, 0, ',', '.') }}</span>
                                    </span>
                                </div>

                                {{-- Judul berita --}}
                                <h3 class="text-base font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-indigo-700 transition duration-200">
                                    {{ $b->judul_berita }}
                                </h3>

                                {{-- Snippet isi berita — strip HTML tag dari TinyMCE, maksimal 2 baris --}}
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
                                    {{ Str::limit(strip_tags($b->isi_berita), 100) }}
                                </p>
                            </div>

                        </article>
                    </a>
                @endforeach
            </div>

            <!-- PAGINATION -->
<div class="mt-12 flex justify-center items-center gap-1.5">
    {{-- Tombol Previous --}}
    @if ($berita->onFirstPage())
        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 cursor-not-allowed text-sm font-medium">&lt;</span>
    @else
        <a href="{{ $berita->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-700 hover:bg-indigo-900 hover:text-white transition-all duration-300 text-sm font-medium shadow-sm">&lt;</a>
    @endif

    {{-- Nomor Halaman --}}
    @foreach ($berita->getUrlRange(1, $berita->lastPage()) as $page => $url)
        @if ($page == 1 || $page == $berita->lastPage() || abs($page - $berita->currentPage()) < 2)
            @if ($page == $berita->currentPage())
                <span class="w-10 h-10 flex items-center justify-center rounded-full bg-indigo-900 text-white font-bold text-sm shadow-md">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-700 hover:bg-gray-100 transition-all duration-300 text-sm font-medium">{{ $page }}</a>
            @endif
        @elseif ($page == 2 || $page == $berita->lastPage() - 1)
            <span class="w-10 h-10 flex items-center justify-center text-gray-400 font-medium">...</span>
        @endif
    @endforeach

    {{-- Tombol Next --}}
    @if ($berita->hasMorePages())
        <a href="{{ $berita->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-700 hover:bg-indigo-900 hover:text-white transition-all duration-300 text-sm font-medium shadow-sm">&gt;</a>
    @else
        <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 cursor-not-allowed text-sm font-medium">&gt;</span>
    @endif
</div>
        </div>

       <aside class="lg:col-span-4 mt-[72px]">
            <div class="border border-gray-200 rounded-2xl p-6 bg-white shadow-sm sticky top-28">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Berita Populer
                </h3>
                
                <div class="space-y-6"> 
                    @foreach($beritaPopuler as $index => $bp)
                    <a href="{{ route('berita.detail', $bp->slug_berita) }}" class="flex gap-5 group cursor-pointer {{ $index > 0 ? 'border-t border-gray-100 pt-5' : '' }}">
                        <div class="text-3xl font-bold text-indigo-200 w-10 flex-shrink-0 text-left">0{{ $index + 1 }}</div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition mb-1 line-clamp-2">
                                {{ $bp->judul_berita }}
                            </h4>
                            <span class="text-[10px] text-indigo-500 block uppercase font-bold tracking-wider">
                                {{ $bp->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.carousel-3d-item');
    let currentIndex = 0;
    
    function update() {
        items.forEach((item, i) => {
            let offset = i - currentIndex;
            if (offset < -items.length/2) offset += items.length;
            if (offset > items.length/2) offset -= items.length;
            
            const caption = item.querySelector('.carousel-caption');
            if (offset === 0) {
                item.style.transform = "translateX(0) scale(1)";
                item.style.zIndex = "30";
                item.style.opacity = "1";
                if(caption) caption.style.opacity = "1";
            } else {
                const move = window.innerWidth < 768 ? (offset > 0 ? '70%' : '-70%') : (offset > 0 ? '32%' : '-32%');
                item.style.transform = `translateX(${move}) scale(0.85)`;
                item.style.zIndex = "20";
                item.style.opacity = "0.4";
                if(caption) caption.style.opacity = "0";
            }
        });
    }

    document.getElementById('next-3d').onclick = () => { currentIndex = (currentIndex + 1) % items.length; update(); };
    document.getElementById('prev-3d').onclick = () => { currentIndex = (currentIndex - 1 + items.length) % items.length; update(); };
    
    setInterval(() => document.getElementById('next-3d').click(), 5000);
    update();
});
</script>

<script>
/**
 * SINKRONISASI VIEWS CARD — home.blade.php
 *
 * Masalah: halaman ini di-cache browser (bfcache) saat user navigasi back
 * dari detail berita. Akibatnya angka views di card tidak berubah meski
 * increment sudah terjadi via AJAX di halaman detail.
 *
 * Solusi: saat pageshow dengan persisted=true (halaman dari bfcache),
 * fetch views terbaru untuk setiap card dan update span-nya.
 * Tidak ada increment di sini — hanya baca data terbaru.
 */
window.addEventListener('pageshow', function (event) {
    if (!event.persisted) return; // Hanya jalankan saat dari bfcache

    // Kumpulkan semua span views yang punya id "views-card-{id}"
    const viewsSpans = document.querySelectorAll('[id^="views-card-"]');

    viewsSpans.forEach(function (span) {
        const idBerita = span.id.replace('views-card-', '');
        const url = '/berita/' + idBerita + '/get-views'; // route berita.getViews

        fetch(url, {
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
        .catch(function () { /* Gagal fetch tidak merusak halaman */ });
    });
});
</script>
@endsection
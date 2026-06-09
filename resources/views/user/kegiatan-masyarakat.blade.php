@extends('layouts.frontend')

@section('content')
<div class="bg-gray-100 py-6 md:py-8 mb-8 md:mb-10 w-full">
    <div class="max-w-[1536px] mx-auto px-6 lg:px-16">
        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-1 md:mb-2 tracking-tight">Kegiatan Masyarakat</h1>
        <p class="text-sm md:text-lg text-gray-700 max-w-2xl">Mendokumentasikan denyut nadi komunitas, perayaan budaya, dan inisiatif gotong royong yang membangun identitas kita bersama.</p>
    </div>
</div>

<div class="max-w-[1536px] mx-auto px-6 lg:px-16 pb-10">

    @if($heroBerita && $heroBerita->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
        
        @if(isset($heroBerita[0]))
        <div class="lg:col-span-2 flex flex-col justify-between">
            <a href="{{ route('berita.detail', $heroBerita[0]->slug_berita) }}" class="block group">
                <div class="relative w-full aspect-[16/10] rounded-2xl overflow-hidden shadow-sm mb-4">
                    <img src="{{ asset('assets/images/berita/'.$heroBerita[0]->gambar_thumbnail) }}" class="w-full h-full object-cover">
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-[8px] font-bold uppercase px-1.5 py-0.5 rounded shadow-sm tracking-wider">
                        {{ $heroBerita[0]->kategori->nama_kategori ?? 'Kegiatan Masyarakat' }}
                    </span>
                </div>
                <div class="mt-2">
                    <div class="flex items-center gap-3 text-xs text-gray-400 font-semibold mb-2 uppercase tracking-wide">
                        <span>{{ date('d M Y', strtotime($heroBerita[0]->created_at)) }}</span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-1.5 normal-case font-medium"
                              id="views-card-{{ $heroBerita[0]->id_berita }}">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="views-text">{{ number_format($heroBerita[0]->views ?? 0, 0, ',', '.') }}</span> Views
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition duration-300">
                        {{ $heroBerita[0]->judul_berita }}
                    </h2>
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 sm:line-clamp-3 mt-2 mb-4">
                        {{ Str::limit(strip_tags($heroBerita[0]->isi_berita), 220) }}
                    </p>
                </div>
            </a>
        </div>
        @endif

        <div class="flex flex-col gap-5">
            @foreach($heroBerita as $index => $hb)
    @if($index > 0)
    <a href="{{ route('berita.detail', $hb->slug_berita) }}" class="block group h-full">
        <article class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm flex flex-col h-full">
            <div class="relative w-full aspect-[16/10] overflow-hidden flex-shrink-0">
                <img src="{{ asset('assets/images/berita/'.$hb->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $hb->judul_berita }}">
                <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-[8px] font-bold uppercase px-1.5 py-0.5 rounded shadow-sm tracking-wider">
                    {{ $hb->kategori->nama_kategori ?? 'Kegiatan Masyarakat' }}
                </span>
            </div>
            
            <div class="p-5 flex flex-col flex-grow">
                <h3 class="text-base font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition line-clamp-2 mb-3">
                    {{ $hb->judul_berita }}
                </h3>
                
                <div class="text-xs font-semibold text-gray-400 pt-3 mt-auto border-t flex justify-between items-center flex-shrink-0">
                    <span>{{ date('d M Y', strtotime($hb->created_at)) }}</span>
                    
                    <span class="flex items-center gap-1 font-medium"
                          id="views-card-{{ $hb->id_berita }}">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        </svg>
                        <span class="views-text">{{ number_format($hb->views ?? 0, 0, ',', '.') }}</span>
                    </span>
                </div>
            </div>
        </article>
    </a>
    @endif
@endforeach
        </div>

    </div>
    @endif

    {{-- FILTER BAR (Gaya Pills & Rata Kanan) --}}
    <div class="flex items-center justify-end gap-3 mb-8">
        
        {{-- TOMBOL TOGGLE WAKTU --}}
        <a href="{{ request()->fullUrlWithQuery(['order' => (request('order') == 'terlama' ? 'terbaru' : 'terlama')]) }}" 
           class="flex items-center gap-2 px-4 py-1.5 border border-gray-200 rounded-full text-xs font-bold text-gray-700 hover:bg-gray-50 transition-all duration-300">
            @if(request('order') == 'terlama')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 1l4 4m0 0l-4 4m4-4H7"/></svg>
                Terlama
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/></svg>
                Terbaru
            @endif
        </a>

        {{-- TOMBOL GRID/LIST --}}
        <div class="flex border border-gray-200 rounded-lg p-1 bg-white shadow-sm">
            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}" class="p-1.5 {{ $viewMode == 'grid' ? 'bg-gray-100 text-indigo-600' : 'text-gray-400' }} rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h6v6H4V4zM14 4h6v6h-6V4zM4 14h6v6H4v-6zM14 14h6v6h-6v-6z"/></svg>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}" class="p-1.5 {{ $viewMode == 'list' ? 'bg-gray-100 text-indigo-600' : 'text-gray-400' }} rounded-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </a>
        </div>
    </div>

    {{-- LIST BERITA (Dinamis: Grid atau List) --}}
    <div class="{{ $viewMode == 'grid' ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6' : 'flex flex-col gap-4 md:gap-6' }}">
        @foreach($listBerita as $b)
        <a href="{{ route('berita.detail', $b->slug_berita) }}" class="{{ $viewMode == 'list' ? 'flex w-full' : 'block h-full' }} group">
            <article class="{{ $viewMode == 'list' ? 'flex flex-row w-full' : 'flex flex-col h-full' }} bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-300">
                <div class="{{ $viewMode == 'list' ? 'w-2/5 sm:w-1/3 min-h-[130px] sm:h-48' : 'w-full aspect-[16/10]' }} relative overflow-hidden flex-shrink-0">
                    <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $b->judul_berita }}">
                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-gray-900 text-[8px] font-bold uppercase px-1.5 py-0.5 rounded shadow-sm tracking-wider">
                        {{ $b->kategori->nama_kategori ?? 'Kegiatan Masyarakat' }}
                    </span>
                </div>
                <div class="{{ $viewMode == 'list' ? 'p-3 sm:p-5' : 'p-5' }} flex flex-col flex-grow space-y-2 sm:space-y-3">
                    <h3 class="{{ $viewMode == 'list' ? 'text-sm sm:text-lg' : 'text-lg' }} font-bold text-gray-900 leading-snug line-clamp-2 group-hover:text-indigo-600 transition">
                        {{ $b->judul_berita }}
                    </h3>
                    @if($viewMode != 'list')
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">{{ Str::limit(strip_tags($b->isi_berita), 100) }}</p>
                    @endif
                   <div class="text-xs font-semibold text-gray-400 pt-2 sm:pt-3 mt-auto border-t flex justify-between items-center flex-shrink-0">
                        <span>{{ date('d M Y', strtotime($b->created_at)) }}</span>
                        <span class="flex items-center gap-1 text-gray-400 font-medium"
                              id="views-card-{{ $b->id_berita }}">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            </svg>
                            <span class="views-text">{{ number_format($b->views ?? 0, 0, ',', '.') }}</span> Views
                        </span>
                    </div>
                </div> 
            </article>
        </a>
        @endforeach 
    </div>

    <div class="mt-12 flex justify-center items-center gap-1.5">
        @if ($listBerita->onFirstPage())
            <span class="w-8 h-8 text-sm flex items-center justify-center rounded-full border border-gray-200 text-gray-400 cursor-not-allowed select-none">&lt;</span>
        @else
            <a href="{{ $listBerita->previousPageUrl() }}" class="w-8 h-8 text-sm flex items-center justify-center rounded-full border border-gray-200 hover:bg-indigo-900 hover:text-white transition-all duration-300">&lt;</a>
        @endif

        @foreach ($listBerita->getUrlRange(1, $listBerita->lastPage()) as $page => $url)
            @if ($page == 1 || $page == $listBerita->lastPage() || abs($page - $listBerita->currentPage()) < 2)
                @if ($page == $listBerita->currentPage())
                    <span class="w-8 h-8 text-sm flex items-center justify-center rounded-full bg-indigo-900 text-white shadow-md font-bold select-none">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 text-sm flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-100 transition-all duration-300">{{ $page }}</a>
                @endif
            @elseif ($page == 2 || $page == $listBerita->lastPage() - 1)
                @if(!isset($dots))
                    <span class="w-8 h-8 text-sm flex items-center justify-center text-gray-400 select-none">...</span>
                    @php $dots = true; @endphp
                @endif
            @endif
        @endforeach

        @if ($listBerita->hasMorePages())
            <a href="{{ $listBerita->nextPageUrl() }}" class="w-8 h-8 text-sm flex items-center justify-center rounded-full border border-gray-200 hover:bg-indigo-900 hover:text-white transition-all duration-300">&gt;</a>
        @else
            <span class="w-8 h-8 text-sm flex items-center justify-center rounded-full border border-gray-200 text-gray-400 cursor-not-allowed select-none">&gt;</span>
        @endif
    </div>
</div>

<script>
/**
 * SINKRONISASI VIEWS CARD — kegiatan-masyarakat.blade.php
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
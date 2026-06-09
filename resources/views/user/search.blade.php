@extends('layouts.frontend')

@section('content')
<div class="bg-gray-100 py-8 mb-10 w-full">
    <div class="max-w-[1536px] mx-auto px-4 lg:px-16">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2 tracking-tight">Hasil Pencarian: "{{ $keyword }}"</h1>
        <p class="text-sm text-gray-600">Menampilkan {{ $listBerita->total() }} berita yang berkaitan.</p>
    </div>
</div>

<div class="max-w-[1536px] mx-auto px-4 lg:px-16 pb-10">

    @if($listBerita->count() > 0)
        <!-- Grid responsif: 1 kolom di mobile, 2 di tablet, 3 di desktop -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($listBerita as $b)
            <article class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full cursor-pointer group">
                <div class="relative w-full aspect-video overflow-hidden">
                    <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $b->judul_berita }}">
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-[10px] font-bold uppercase px-2 py-1 rounded shadow-sm">
                        {{ $b->kategori->nama_kategori ?? 'Umum' }}
                    </span>
                </div>
                
                <div class="p-5 flex flex-col flex-grow">
                    <a href="{{ route('berita.detail', $b->slug_berita) }}" class="block mb-2">
                        <h3 class="text-lg font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition">
                            {{ $b->judul_berita }}
                        </h3>
                    </a>

                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mt-auto mb-4">
                        {{ Str::limit(strip_tags($b->isi_berita), 100) }}
                    </p>

                    <div class="text-xs font-semibold text-gray-400 pt-4 mt-4 border-t flex justify-between items-center">
                        <span>{{ date('d M Y', strtotime($b->created_at)) }}</span>
                        <span class="flex items-center gap-1 text-gray-400 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ number_format($b->views ?? 0, 0, ',', '.') }} Views
                        </span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-12 flex flex-wrap justify-center items-center gap-1.5">
            @if ($listBerita->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 cursor-not-allowed text-sm">&lt;</span>
            @else
                <a href="{{ $listBerita->appends(['keyword' => $keyword, 'id_kategori' => $id_kategori])->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 hover:bg-indigo-900 hover:text-white transition text-sm">&lt;</a>
            @endif

            @foreach ($listBerita->getUrlRange(1, $listBerita->lastPage()) as $page => $url)
                @if ($page == 1 || $page == $listBerita->lastPage() || abs($page - $listBerita->currentPage()) < 2)
                    @if ($page == $listBerita->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-indigo-900 text-white font-bold text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $listBerita->appends(['keyword' => $keyword, 'id_kategori' => $id_kategori])->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-100 transition text-sm">{{ $page }}</a>
                    @endif
                @endif
            @endforeach

            @if ($listBerita->hasMorePages())
                <a href="{{ $listBerita->appends(['keyword' => $keyword, 'id_kategori' => $id_kategori])->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 hover:bg-indigo-900 hover:text-white transition text-sm">&gt;</a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 cursor-not-allowed text-sm">&gt;</span>
            @endif
        </div>
    @else
        <div class="text-center py-20 bg-white border border-gray-100 rounded-3xl shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Berita Tidak Ditemukan</h3>
            <p class="text-sm text-gray-500">Coba gunakan kata kunci lain.</p>
        </div>
    @endif
</div> 
@endsection
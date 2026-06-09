<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita - Smdaily</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex min-h-screen">
{{-- Toast hanya untuk session success (setelah redirect dari AJAX berhasil) --}}
@if (session('success'))
    <div id="toastNotification" class="fixed top-20 right-5 z-[100] w-96">
        <div class="bg-green-600 text-white p-4 rounded-lg shadow-lg flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()"><i class="fas fa-times ml-2"></i></button>
        </div>
    </div>
    <script>
        setTimeout(function() {
            var toast = document.getElementById('toastNotification');
            if (toast) toast.style.display = 'none';
        }, 4000);
    </script>
@endif

    @include('partials.admin.sidebar')

    <div class="flex-1 ml-0 md:ml-64 flex flex-col transition-all">
        @include('partials.admin.nav-admin')

        <main class="p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Daftar Publikasi Berita</h1>
                    <p class="text-gray-500 mt-1">Kelola dan pantau semua konten berita Smdaily di sini.</p>
                </div>
                <button onclick="toggleModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center shadow-md transform active:scale-95 transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Berita Baru
                </button>
            </div>

            <!-- GRID CARD STATISTIK (MANUAL TAPI DINAMIS) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="relative bg-blue-600 p-6 rounded-lg text-white shadow-md overflow-hidden">
        <p class="text-sm opacity-80 mb-2">Total Berita</p>
        <h2 class="text-4xl font-bold mb-2">{{ number_format($totalBerita, 0, ',', '.') }}</h2>
        <p class="text-xs opacity-80"><i class="fas fa-database mr-1"></i> Semua Status Data</p>
    </div>
    
    @foreach($kategoriData as $index => $kat)
    @php
        // Palet warna tanpa Ijo/Abu (Sky, Indigo, Amber, Rose)
        $activePool = [
            ['bg-sky-50', 'text-sky-800', 'text-sky-900', 'bg-sky-500', 'bg-sky-100', 'text-sky-700'],
            ['bg-indigo-50', 'text-indigo-800', 'text-indigo-900', 'bg-indigo-500', 'bg-indigo-100', 'text-indigo-700'],
            ['bg-amber-50', 'text-amber-800', 'text-amber-900', 'bg-amber-500', 'bg-amber-100', 'text-amber-700'],
            ['bg-rose-50', 'text-rose-800', 'text-rose-900', 'bg-rose-500', 'bg-rose-100', 'text-rose-700']
        ];
        
        $c = ($kat->berita_count == 0) 
            ? ['bg-red-50', 'text-red-700', 'text-red-900', 'bg-red-500', 'bg-red-100', 'text-red-700']
            : $activePool[$index % count($activePool)];
    @endphp
    
    <div class="{{ $c[0] }} p-6 rounded-lg border border-transparent shadow-sm flex flex-col justify-between">
        <div>
            <p class="text-sm {{ $c[1] }} font-semibold mb-1">{{ $kat->nama_kategori }}</p>
            <h2 class="text-2xl font-bold {{ $c[2] }} flex items-baseline gap-1.5">
                {{ $kat->berita_count }} <span class="text-xs font-semibold {{ $c[1] }}/80 normal-case">Berita</span>
            </h2>
        </div>
        <div class="w-full bg-black/5 h-1.5 rounded-full mt-4">
            <div class="h-1.5 {{ $c[3] }} rounded-full opacity-60" style="width: {{ $totalBerita > 0 ? ($kat->berita_count / $totalBerita) * 100 : 0 }}%"></div>
        </div>
    </div>
    @endforeach
</div>
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
               <form method="GET" action="{{ url()->current() }}" class="p-6 border-b border-gray-100 flex flex-wrap items-center gap-4 bg-gray-50/50">
    
    <div class="flex flex-wrap items-center gap-4">
        <select name="kategori" onchange="this.form.submit()" class="border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium bg-white focus:outline-none cursor-pointer">
            <option value="">📁 Semua Kategori</option>
            @foreach($kategoriList as $kat)
                <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </select>

        <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()" class="border border-gray-200 px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 focus:outline-none cursor-pointer">

        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Berita..." class="border border-gray-200 rounded-lg pl-4 pr-10 py-2 text-sm w-64 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <button type="submit" class="absolute right-3 top-2.5 text-gray-400">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    @if(request('tanggal') || request('kategori') || request('search'))
        <div class="ml-auto">
            <a href="{{ url()->current() }}" class="flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-semibold shadow-sm transition transform active:scale-95">
                <i class="fas fa-undo-alt text-[10px]"></i> Reset Filter
            </a>
        </div>
    @endif

</form>
                
                <div class="overflow-x-auto w-full">
                <table class="w-full min-w-[750px] text-left">
                    <thead class="bg-gray-100 text-gray-600 text-xs uppercase font-semibold">
    <tr>
        <th class="px-6 py-4">Judul Berita</th>
        <th class="px-6 py-4">Kategori</th>
        <th class="px-6 py-4">Status</th>
        <th class="px-6 py-4">Rilis</th>
        <th class="px-6 py-4">Diperbarui</th>
        <th class="px-6 py-4 text-center">Aksi</th>
    </tr>
</thead>
<tbody class="divide-y divide-gray-200 text-sm">
    @forelse($listBerita as $b)
    <tr class="hover:bg-gray-50/80 transition">
        <td class="px-6 py-4 font-medium flex items-center">
            <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}" class="w-10 h-10 rounded object-cover mr-3">
            {{ Str::limit($b->judul_berita, 30) }}
        </td>

        <td class="px-6 py-4">
            @php
                // Palet warna SAMA PERSIS dengan card statistik kategori di atas tabel
                $paletTag = [
                    ['bg-sky-100', 'text-sky-700'],
                    ['bg-indigo-100', 'text-indigo-700'],
                    ['bg-amber-100', 'text-amber-700'],
                    ['bg-rose-100', 'text-rose-700'],
                ];
                // Cari index kategori ini dari $kategoriList agar warna konsisten
                $idxKat = 0;
                foreach ($kategoriList as $ki => $kItem) {
                    if ($kItem->id_kategori == $b->id_kategori) { $idxKat = $ki; break; }
                }
                // Jika berita_count == 0 pada card, card pakai merah — tag ikut merah
                $isKosong = isset($kategoriData[$idxKat]) && $kategoriData[$idxKat]->berita_count == 0;
                $warnaTag = $isKosong
                    ? ['bg-red-100', 'text-red-700']
                    : $paletTag[$idxKat % count($paletTag)];
            @endphp
            <span class="{{ $warnaTag[0] }} {{ $warnaTag[1] }} px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                {{ $b->kategori->nama_kategori ?? 'Umum' }}
            </span>
        </td>

        <td class="px-6 py-4">
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $b->status == 'publish' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                {{ $b->status == 'publish' ? 'Dipublikasi' : 'Draft' }}
            </span>
        </td>

        <td class="px-6 py-4 text-gray-500 text-xs">
            {{ \Carbon\Carbon::parse($b->created_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}
        </td>

        <td class="px-6 py-4 text-gray-500 text-xs">
            @if($b->updated_at)
                <span class="text-blue-600 font-semibold">
                    {{ \Carbon\Carbon::parse($b->updated_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}
                </span>
            @else
                <span class="text-gray-400 italic text-[10px]">Belum diedit</span>
            @endif
        </td>

        <td class="px-4 py-4">
            <div class="flex items-center justify-center gap-2 flex-nowrap">
                {{-- Tombol Edit --}}
                <button type="button"
                    onclick="toggleModal(true, 'edit', {{ json_encode($b) }})"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-semibold transition whitespace-nowrap">
                    <i class="fas fa-edit text-[10px]"></i> Edit
                </button>
                {{-- Tombol Hapus --}}
                <form action="{{ route('berita.delete', $b->id_berita) }}" method="POST" class="inline m-0" id="delete-form-{{ $b->id_berita }}">
                    @csrf @method('DELETE')
                    <button type="button"
                        onclick="confirmDelete({{ $b->id_berita }})"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition whitespace-nowrap">
                        <i class="fas fa-trash text-[10px]"></i> Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-10 text-center text-gray-400">Tidak ada data.</td>
    </tr>
    @endforelse
</tbody>
                </table>
                </div>{{-- /overflow-x-auto --}}
  
                <div class="pt-12 pb-8 border-t border-gray-100 bg-gray-50/50 flex justify-center">
                    <div class="flex items-center space-x-1">
                        @if ($listBerita->onFirstPage())
                            <span class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed select-none">&lt; Sebelumnya</span>
                        @else
                            <a href="{{ $listBerita->previousPageUrl() }}" class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">&lt; Sebelumnya</a>
                        @endif

                        @foreach ($listBerita->getUrlRange(1, $listBerita->lastPage()) as $page => $url)
                            @if ($page == 1 || $page == $listBerita->lastPage() || abs($page - $listBerita->currentPage()) < 2)
                                @if ($page == $listBerita->currentPage())
                                    <span class="h-9 w-9 text-xs flex items-center justify-center rounded-lg bg-blue-600 text-white font-bold shadow-sm select-none">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="h-9 w-9 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">{{ $page }}</a>
                                @endif
                            @elseif ($page == 2 || $page == $listBerita->lastPage() - 1)
                                @if(!isset($dots))
                                    <span class="h-9 w-9 text-xs flex items-center justify-center text-gray-400 select-none">...</span>
                                    @php $dots = true; @endphp
                                @endif
                            @endif
                        @endforeach

                        @if ($listBerita->hasMorePages())
                            <a href="{{ $listBerita->nextPageUrl() }}" class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">Berikutnya &gt;</a>
                        @else
                            <span class="h-9 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed select-none">Berikutnya &gt;</span>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('partials.admin.modal-tambah-berita')

   <script>
    function toggleModal(show, mode = 'create', berita = null) {
    const modal = document.getElementById('modal-tambah-berita');
    const form = document.querySelector('#modal-tambah-berita form');
    const title = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');
    const tanggalInput = document.getElementById('tanggalInput');

    // Ambil format tanggal hari ini secara real-time (WITA / Asia/Makassar) -> Format: YYYY-MM-DD
    const hariIni = new Date().toLocaleDateString('sv-SE', { timeZone: 'Asia/Makassar' });

    if (show) {
        modal.classList.remove('hidden');
        
        if (mode === 'edit' && berita) {
            // ==========================================
            // MODE EDIT BERITA (UPDATE)
            // ==========================================
            if (typeof bersihkanSemuaError === 'function') bersihkanSemuaError();
            document.getElementById('namaFileGambar').classList.add('hidden');
            title.innerText = "Edit Berita";
            form.action = "{{ route('berita.update', '') }}/" + berita.id_berita;
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Isi data field teks ke form
            form.querySelector('[name="judul"]').value = berita.judul_berita;
            form.querySelector('[name="penulis"]').value = berita.penulis;
            form.querySelector('[name="kategori"]').value = berita.id_kategori;
            form.querySelector('[name="status"]').value = berita.status;
            
            // Ambil data tanggal asli dari database berita tersebut
            const tanggalAsliBerita = berita.created_at.split(' ')[0];

            // JIKA tanggal asli berita sudah lewat dari hari ini, tetap tampilkan tanggal aslinya,
            // TETAPI kalender dikunci agar admin HANYA BISA mengubah ke tanggal hari ini atau maju ke depan.
            tanggalInput.value = tanggalAsliBerita;
            tanggalInput.setAttribute('min', hariIni);
            
            if (typeof tinymce !== 'undefined' && tinymce.get('isiBerita')) {
                tinymce.get('isiBerita').setContent(berita.isi_berita);
            }
            
        } else {
            // ==========================================
            // MODE TAMBAH BERITA BARU (CREATE)
            // ==========================================
            title.innerText = "Tambah Berita Baru";
            form.action = "{{ route('berita.store') }}";
            methodContainer.innerHTML = '';

            // Reset form dan bersihkan error lama setiap kali modal create dibuka
            form.reset();
            if (typeof bersihkanSemuaError === 'function') bersihkanSemuaError();
            document.getElementById('namaFileGambar').classList.add('hidden');
            tanggalInput.value = hariIni;
            tanggalInput.setAttribute('min', hariIni);

            if (typeof tinymce !== 'undefined' && tinymce.get('isiBerita')) {
                tinymce.get('isiBerita').setContent('');
            }
        }
    } else {
        modal.classList.add('hidden');
    }
}
</script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin hapus berita ini?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    })
}
</script>

{{-- Blok auto-buka modal via $errors dihapus karena error kini ditangani AJAX inline --}}
</body>
</html>
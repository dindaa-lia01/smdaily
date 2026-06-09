<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Smdaily</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex min-h-screen">

    @include('partials.admin.sidebar')

    <div class="flex-1 ml-0 md:ml-64 flex flex-col transition-all duration-300 min-w-0">
        @include('partials.admin.nav-admin')

        <main class="p-4 md:p-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ringkasan Data</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Selamat datang kembali, berikut adalah performa portal berita hari ini.</p>
                </div>
                <div class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-medium text-gray-700 shadow-sm flex items-center gap-2 whitespace-nowrap self-start sm:self-auto">
                    <i class="far fa-calendar-alt text-orange-500"></i>
                    <span id="currentDate">Memuat...</span>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

                {{-- Card 1: Total Berita Dipublikasi — biru --}}
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 shadow-md relative overflow-hidden">
                    {{-- Ikon dekoratif latar --}}
                    <div class="absolute right-3 top-3 opacity-[0.12]">
                        <i class="fas fa-newspaper text-white" style="font-size:80px;"></i>
                    </div>
                    {{-- Label --}}
                    <p class="text-xs text-blue-100 uppercase tracking-wider font-semibold mb-3">Total Berita Dipublikasi</p>
                    {{-- Angka + satuan --}}
                    <div class="flex items-baseline gap-2 mb-4">
                        <h2 class="text-4xl font-bold text-white">{{ number_format($totalPublish, 0, ',', '.') }}</h2>
                        <span class="text-sm font-semibold text-blue-200">Berita</span>
                    </div>
                    {{-- Footer info --}}
                    <div class="flex items-center gap-2 text-xs text-blue-100 font-semibold">
                        <span class="flex items-center gap-1 bg-white/10 rounded-full px-2.5 py-1">
                            <i class="fas fa-arrow-trend-up text-emerald-300"></i>
                            <span>Sudah Tayang</span>
                        </span>
                    </div>
                </div>

                {{-- Card 2: Total Kunjungan Pembaca — abu gelap/slate --}}
                <div class="bg-gradient-to-br from-slate-700 to-slate-800 rounded-2xl p-6 shadow-md relative overflow-hidden">
                    {{-- Ikon dekoratif latar --}}
                    <div class="absolute right-3 top-3 opacity-[0.12]">
                        <i class="fas fa-eye text-white" style="font-size:80px;"></i>
                    </div>
                    {{-- Label --}}
                    <p class="text-xs text-slate-300 uppercase tracking-wider font-semibold mb-3">Total Kunjungan Pembaca</p>
                    {{-- Angka + satuan --}}
                    <div class="flex items-baseline gap-2 mb-4">
                        <h2 class="text-4xl font-bold text-white">
                            @if($totalViews >= 1000)
                                {{ number_format($totalViews / 1000, 1, '.', '') }}K
                            @else
                                {{ number_format($totalViews, 0, ',', '.') }}
                            @endif
                        </h2>
                        <span class="text-sm font-semibold text-slate-300">Kunjungan</span>
                    </div>
                    {{-- Footer info dengan ikon views --}}
                    <div class="flex items-center gap-2 text-xs text-slate-300 font-semibold">
                        <span class="flex items-center gap-1 bg-white/10 rounded-full px-2.5 py-1">
                            <i class="fas fa-eye text-sky-300"></i>
                            <span>Akumulasi Tayangan</span>
                        </span>
                    </div>
                </div>

                {{-- Card 3: Total Kategori Aktif — indigo --}}
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 shadow-md relative overflow-hidden">
                    {{-- Ikon dekoratif latar --}}
                    <div class="absolute right-3 top-3 opacity-[0.12]">
                        <i class="fas fa-layer-group text-white" style="font-size:80px;"></i>
                    </div>
                    {{-- Label --}}
                    <p class="text-xs text-indigo-100 uppercase tracking-wider font-semibold mb-3">Total Kategori Aktif</p>
                    {{-- Angka + satuan --}}
                    <div class="flex items-baseline gap-2 mb-4">
                        <h2 class="text-4xl font-bold text-white">{{ $totalKategori }}</h2>
                        <span class="text-sm font-semibold text-indigo-200">Kategori</span>
                    </div>
                    {{-- Footer info --}}
                    <div class="flex items-center gap-2 text-xs text-indigo-100 font-semibold">
                        <span class="flex items-center gap-1 bg-white/10 rounded-full px-2.5 py-1">
                            <i class="fas fa-circle-check text-emerald-300"></i>
                            <span>Semua Aktif</span>
                        </span>
                    </div>
                </div>

            </div>

            {{-- Tabel Aktivitas Terakhir --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-900">Aktivitas Terakhir</h3>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3">Thumbnail</th>
                                <th class="px-5 py-3">Judul Berita</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($berita as $b)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                {{-- Thumbnail --}}
                                <td class="px-5 py-3">
                                    @if(!empty($b->gambar_thumbnail))
                                        <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}"
                                             alt="{{ $b->judul_berita }}"
                                             class="w-12 h-9 rounded-lg object-cover shadow-sm border border-gray-100">
                                    @else
                                        <div class="w-12 h-9 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                            <i class="fas fa-image text-gray-300 text-sm"></i>
                                        </div>
                                    @endif
                                </td>
                                {{-- Judul --}}
                                <td class="px-5 py-3 font-medium text-gray-800">
                                    {{ Str::limit($b->judul_berita, 50) }}
                                </td>
                                {{-- Status --}}
                                <td class="px-5 py-3">
                                    @if($b->status == 'publish')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Dipublikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                {{-- Waktu aktivitas: updated_at = saat dibuat/diedit (diisi eksplisit BeritaController)
                                     fallback created_at jika kosong | <24 jam: "2 jam lalu" | >=24 jam: "8 Juni 2026" --}}
                                <td class="px-5 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    @php
                                        $waktu = $b->updated_at ?? $b->created_at;
                                        $carbon = \Carbon\Carbon::parse($waktu)->timezone('Asia/Makassar');
                                        $tampilWaktu = $carbon->locale('id')->diffInHours(now()) < 24
                                            ? $carbon->locale('id')->diffForHumans()
                                            : $carbon->translatedFormat('d F Y');
                                    @endphp
                                    {{ $tampilWaktu }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($berita as $b)
                    @php
                        $waktu = $b->updated_at ?? $b->created_at;
                        $carbon = \Carbon\Carbon::parse($waktu)->timezone('Asia/Makassar');
                        $tampilWaktu = $carbon->locale('id')->diffInHours(now()) < 24
                            ? $carbon->locale('id')->diffForHumans()
                            : $carbon->translatedFormat('d F Y');
                    @endphp
                    <div class="px-4 py-4 flex items-center gap-3">
                        {{-- Thumbnail mobile --}}
                        @if(!empty($b->gambar_thumbnail))
                            <img src="{{ asset('assets/images/berita/'.$b->gambar_thumbnail) }}"
                                 alt="{{ $b->judul_berita }}"
                                 class="w-14 h-12 rounded-lg object-cover flex-shrink-0 border border-gray-100 shadow-sm">
                        @else
                            <div class="w-14 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 border border-gray-200">
                                <i class="fas fa-image text-gray-300 text-sm"></i>
                            </div>
                        @endif
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2">{{ $b->judul_berita }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $tampilWaktu }}</p>
                        </div>
                        {{-- Status badge --}}
                        <div class="flex-shrink-0">
                            @if($b->status == 'publish')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Dipublikasi</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">Draft</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/40">
                    <a href="/admin/man-berita" class="flex items-center justify-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                        Muat Lebih Banyak <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        </main>
    </div>

    {{-- ============================================================
         FAB Button "+" — membuka modal tambah berita baru
         toggleModal() sudah dideklarasikan di bawah dan kompatibel
         penuh dengan modal-tambah-berita.blade.php yang ada.
    ============================================================ --}}
    <button type="button" onclick="toggleModal(true)"
        class="fixed bottom-6 right-6 md:bottom-8 md:right-8 bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-xl hover:bg-blue-700 active:scale-95 transition-all duration-150 z-40"
        title="Tambah Berita Baru">
        <i class="fas fa-plus text-xl"></i>
    </button>

    @include('partials.admin.modal-tambah-berita')

    <script>
    /**
     * toggleModal — membuka/menutup modal tambah-berita.
     * Mendukung dua mode:
     *   toggleModal(true)              → buka dalam mode CREATE (tambah baru)
     *   toggleModal(true, 'edit', obj) → buka dalam mode EDIT  (isi data bestehend)
     *   toggleModal(false)             → tutup modal
     *
     * Fungsi ini kompatibel dengan semua pemanggil di man-berita.blade.php
     * dan modal-tambah-berita.blade.php tanpa perlu mengubah file tersebut.
     */
    function toggleModal(show, mode = 'create', berita = null) {
        const modal = document.getElementById('modal-tambah-berita');
        if (!modal) {
            console.error("Modal 'modal-tambah-berita' tidak ditemukan.");
            return;
        }

        const form        = modal.querySelector('form');
        const title       = document.getElementById('modalTitle');
        const methodCont  = document.getElementById('methodContainer');
        const tanggalInp  = document.getElementById('tanggalInput');
        const hariIni     = new Date().toLocaleDateString('sv-SE', { timeZone: 'Asia/Makassar' });

        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (mode === 'edit' && berita) {
                // ── MODE EDIT ──────────────────────────────────────────
                if (typeof bersihkanSemuaError === 'function') bersihkanSemuaError();
                document.getElementById('namaFileGambar')?.classList.add('hidden');

                title.innerText  = 'Edit Berita';
                form.action      = "{{ route('berita.update', '') }}/" + berita.id_berita;
                methodCont.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                form.querySelector('[name="judul"]').value    = berita.judul_berita;
                form.querySelector('[name="penulis"]').value  = berita.penulis;
                form.querySelector('[name="kategori"]').value = berita.id_kategori;
                form.querySelector('[name="status"]').value   = berita.status;

                const tanggalAsli = berita.created_at.split(' ')[0];
                tanggalInp.value = tanggalAsli;
                tanggalInp.setAttribute('min', hariIni);

                if (typeof tinymce !== 'undefined' && tinymce.get('isiBerita')) {
                    tinymce.get('isiBerita').setContent(berita.isi_berita || '');
                }

            } else {
                // ── MODE CREATE ────────────────────────────────────────
                if (title)      title.innerText = 'Tambah Berita Baru';
                if (form)       form.action     = "{{ route('berita.store') }}";
                if (methodCont) methodCont.innerHTML = '';

                form?.reset();
                if (typeof bersihkanSemuaError === 'function') bersihkanSemuaError();
                document.getElementById('namaFileGambar')?.classList.add('hidden');

                if (tanggalInp) {
                    tanggalInp.value = hariIni;
                    tanggalInp.setAttribute('min', hariIni);
                }

                if (typeof tinymce !== 'undefined' && tinymce.get('isiBerita')) {
                    tinymce.get('isiBerita').setContent('');
                }
            }

        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
    </script>

    <script>
        // Set tanggal hari ini di header bar
        function updateDate() {
            const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').innerText =
                new Date().toLocaleDateString('id-ID', opts);
        }
        updateDate();
    </script>

</body>
</html>
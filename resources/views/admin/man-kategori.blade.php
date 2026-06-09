<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori - Smdaily Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex min-h-screen">

    @include('partials.admin.sidebar')

    <div class="flex-1 ml-0 md:ml-64 flex flex-col">
        @include('partials.admin.nav-admin')

        <main class="p-4 md:p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Manajemen Kategori</h1>

            @if(session('success'))
                <div id="flash-message" class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex justify-between items-center shadow-sm transition-all duration-500">
                    <div class="flex items-center"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</div>
                    <button onclick="document.getElementById('flash-message').remove()" class="text-green-500 hover:text-green-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-message" class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg flex justify-between items-center shadow-sm transition-all duration-500">
                    <div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</div>
                    <button onclick="document.getElementById('flash-message').remove()" class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-4 md:p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50/50 gap-3">
    <div>
        <h2 class="text-lg font-bold">Daftar Kategori</h2>
        <p class="text-sm text-gray-500">Kelola kategori berita utama untuk klasifikasi editorial.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
        <!-- Form Search -->
        <form action="{{ route('kategori.index') }}" method="GET" class="relative w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari Kategori..." 
                   class="border border-gray-300 rounded-lg pl-4 pr-10 py-2 text-sm w-full sm:w-56 shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
            <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-blue-600">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <button type="button" onclick="openKategoriModal('create')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center justify-center shadow-md hover:bg-blue-700 transition transform active:scale-95 w-full sm:w-auto">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori Baru
        </button>
    </div>
</div>

                <div class="overflow-x-auto w-full">
                <table class="w-full min-w-[600px] text-left">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4">Slug (URL)</th>
                            <th class="px-6 py-4">Jumlah Berita</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($listKategori as $index => $kat)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">
                                {{ $listKategori->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $kat->nama_kategori }}</td>
                            <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $kat->slug_kategori }}</td>
                            <td class="px-6 py-4 text-gray-700 font-semibold">
                                {{ $kat->berita_count }} Artikel
                            </td>
                            <td class="px-6 py-4">
                                @if($kat->status == 'aktif')
                                    <span class="bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-xs font-bold flex w-max items-center uppercase tracking-wider">
                                        <i class="fas fa-circle text-[6px] mr-1.5 text-blue-500"></i> Aktif
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 border border-gray-200 px-3 py-1 rounded-full text-xs font-bold flex w-max items-center uppercase tracking-wider">
                                        <i class="fas fa-circle text-[6px] mr-1.5 text-gray-400"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                <button onclick="openKategoriModal('edit', {{ $kat->id_kategori }}, '{{ $kat->nama_kategori }}', '{{ $kat->status }}')" class="bg-blue-50 text-blue-600 p-2 rounded hover:bg-blue-100 transition transform active:scale-90">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="triggerPopUpDelete({{ $kat->id_kategori }}, '{{ $kat->nama_kategori }}')" class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-100 transition transform active:scale-90">
                                    <i class="fas fa-trash"></i>
                                </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 font-medium bg-gray-50/30">
                                <i class="fas fa-tags text-2xl mb-2 block text-gray-300"></i> Tidak ada kategori yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>{{-- end overflow-x-auto --}}

                <div class="p-4 md:p-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-3 bg-gray-50/50">
                    <span class="text-sm text-gray-500 font-medium">
                        @if($listKategori->total() <= $listKategori->perPage())
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i> Total: <strong>{{ $listKategori->total() }}</strong> Kategori tersedia
                        @else
                            Menampilkan <strong>{{ $listKategori->firstItem() }}</strong> - <strong>{{ $listKategori->lastItem() }}</strong> dari <strong>{{ $listKategori->total() }}</strong> kategori
                        @endif
                    </span>
                    
                    <div class="flex items-center space-x-1">
                        @if ($listKategori->onFirstPage())
                            <span class="h-8 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed select-none">&lt; Sebelumnya</span>
                        @else
                            <a href="{{ $listKategori->previousPageUrl() }}" class="h-8 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition shadow-sm">&lt; Sebelumnya</a>
                        @endif

                        @foreach ($listKategori->getUrlRange(1, $listKategori->lastPage()) as $page => $url)
                            @if ($page == $listKategori->currentPage())
                                <span class="h-8 w-8 text-xs flex items-center justify-center rounded-lg bg-blue-600 text-white font-bold shadow-sm select-none">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="h-8 w-8 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition shadow-sm">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($listKategori->hasMorePages())
                            <a href="{{ $listKategori->nextPageUrl() }}" class="h-8 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition shadow-sm">Berikutnya &gt;</a>
                        @else
                            <span class="h-8 px-3 text-xs flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 cursor-not-allowed select-none">Berikutnya &gt;</span>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="modalKategori" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in duration-300">
            <form id="formKategori" method="POST" action="" onsubmit="return jalankanValidasiFront()">
                @csrf
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Kategori Baru</h2>
                    <button type="button" onclick="closeKategoriModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="inputNamaKategori" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Contoh: Otomotif">
                        <p id="errorNamaKategori" class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1">
                            <i class="fas fa-exclamation-triangle"></i> <span></span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status" id="inputStatus" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="p-6 border-t flex justify-end gap-3 bg-gray-50/50">
                    <button type="button" onclick="closeKategoriModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-100 rounded-lg transition">Batal</button>
                    <button type="submit" id="btnSimpan" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalDeleteKategori" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all duration-300 p-6 text-center">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-trash-alt animate-bounce"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Hapus Data</h3>
            <p class="text-gray-500 text-sm mb-6">Apakah Anda benar-benar yakin ingin menghapus kategori <span id="deleteTargetName" class="font-bold text-red-600"></span>? Data yang terhapus tidak dapat dikembalikan.</p>
            
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-xl text-sm hover:bg-gray-200 transition">Batal, Amankan</button>
                <button type="button" onclick="executeDeleteKategori()" class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-xl text-sm hover:bg-red-700 shadow-md transition transform active:scale-95">Ya, Hapus Data</button>
            </div>
        </div>
    </div>

    <form id="formDeleteKategori" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let selectedDeleteId = null;

        function openKategoriModal(mode, id = null, nama = '', status = 'aktif') {
            const modal = document.getElementById('modalKategori');
            const form = document.getElementById('formKategori');
            const title = document.getElementById('modalTitle');
            const btn = document.getElementById('btnSimpan');
            resetFormErrors();

            if (mode === 'create') {
                title.innerText = "Tambah Kategori Baru";
                form.action = "{{ route('kategori.store') }}";
                document.getElementById('inputNamaKategori').value = "";
                document.getElementById('inputStatus').value = "aktif";
                btn.innerText = "Simpan Kategori";
            } else if (mode === 'edit') {
                title.innerText = "Ubah Kategori";
                form.action = "/admin/kategori/update/" + id;
                document.getElementById('inputNamaKategori').value = nama;
                document.getElementById('inputStatus').value = status;
                btn.innerText = "Perbarui Kategori";
            }
            modal.classList.remove('hidden');
        }

        function closeKategoriModal() {
            document.getElementById('modalKategori').classList.add('hidden');
        }

        function resetFormErrors() {
            const input = document.getElementById('inputNamaKategori');
            const error = document.getElementById('errorNamaKategori');
            error.classList.add('hidden');
            input.classList.remove('border-red-500', 'focus:ring-red-500');
        }

        function jalankanValidasiFront() {
            const input = document.getElementById('inputNamaKategori');
            const error = document.getElementById('errorNamaKategori');
            const regex = /^[A-Za-z\s]+$/;

            if (input.value.trim() === "") {
                error.querySelector('span').innerText = "Nama kategori tidak boleh kosong!";
                error.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }
            if (!regex.test(input.value)) {
                error.querySelector('span').innerText = "Hanya diperbolehkan huruf dan spasi!";
                error.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }
            
            // PERBAIKAN: Begitu sukses divalidasi, langsung hilangkan modal pop-up agar tidak menumpuk di screen
            closeKategoriModal();
            return true;
        }

        // LOGIKA MODEL POP-UP DELETE BARU
        function triggerPopUpDelete(id, nama) {
            selectedDeleteId = id;
            document.getElementById('deleteTargetName').innerText = nama;
            document.getElementById('modalDeleteKategori').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('modalDeleteKategori').classList.add('hidden');
            selectedDeleteId = null;
        }

        function executeDeleteKategori() {
            if(selectedDeleteId) {
                const deleteForm = document.getElementById('formDeleteKategori');
                deleteForm.action = "/admin/kategori/delete/" + selectedDeleteId;
                closeDeleteModal(); // Hilangkan pop-up langsung begitu diklik
                deleteForm.submit();
            }
        }

        // OTOMATIS HILANGKAN ALERT SUCCESS/ERROR SETELAH 3 DETIK (BIAR GAK BERTAHAN LAMA)
        window.addEventListener('DOMContentLoaded', () => {
            const flash = document.getElementById('flash-message');
            if(flash) {
                setTimeout(() => {
                    flash.style.opacity = '0';
                    setTimeout(() => flash.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>
<div id="modal-tambah-berita" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] overflow-hidden">
        
        {{-- 
            Form tidak lagi punya action/method tetap di sini.
            Action URL diset secara dinamis oleh toggleModal() sesuai mode create/edit.
            Submit ditangani oleh AJAX (lihat script di bawah), bukan submit biasa.
        --}}
        <form id="formBerita" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div id="methodContainer"></div>

            <div class="p-6 border-b flex justify-between items-center shrink-0">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Berita Baru</h2>
                <button type="button" onclick="toggleModal(false)" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 space-y-4 overflow-y-auto flex-1">

                {{-- 
                    Kotak ringkasan error AJAX — tersembunyi secara default.
                    Muncul otomatis di atas form jika ada error dari server.
                --}}
                <div id="ajaxErrorBox" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm font-bold text-red-700 mb-2 flex items-center gap-1.5">
                        <i class="fas fa-exclamation-triangle"></i> Mohon perbaiki kesalahan berikut:
                    </p>
                    <ul id="ajaxErrorList" class="list-disc ml-5 text-sm text-red-600 space-y-0.5"></ul>
                </div>

                {{-- Field: Gambar Thumbnail --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gambar Thumbnail</label>
                    {{-- 
                        Tidak pakai @error() di sini karena error sekarang ditangani AJAX.
                        Kelas border-red-500 ditambah/lepas secara dinamis via JS.
                    --}}
                    <input type="file" name="gambar" id="inputGambar" accept="image/*"
                           class="w-full border p-2 rounded transition">
                    <p id="err-gambar" class="hidden text-red-500 text-xs mt-1"></p>
                    {{-- Preview nama file yang sudah dipilih agar tidak hilang saat error muncul --}}
                    <p id="namaFileGambar" class="hidden text-xs text-gray-500 mt-1 italic"></p>
                </div>

                {{-- Field: Judul Berita --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Judul Berita</label>
                    <input type="text" name="judul" id="inputJudul"
                           class="w-full border p-2 rounded transition">
                    <p id="err-judul" class="hidden text-red-500 text-xs mt-1"></p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Field: Kategori --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                        <select name="kategori" id="inputKategori" class="w-full border p-2 rounded transition">
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <p id="err-kategori" class="hidden text-red-500 text-xs mt-1"></p>
                    </div>
                    {{-- Field: Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status" id="inputStatus" class="w-full border p-2 rounded transition">
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                        <p id="err-status" class="hidden text-red-500 text-xs mt-1"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Field: Tanggal Rilis --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Rilis</label>
                        <input type="date" name="tanggal" id="tanggalInput"
                               min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                               class="w-full border p-2 rounded transition">
                        <p id="err-tanggal" class="hidden text-red-500 text-xs mt-1"></p>
                    </div>
                    {{-- Field: Penulis --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Penulis</label>
                        <input type="text" name="penulis" id="inputPenulis"
                               class="w-full border p-2 rounded transition">
                        <p id="err-penulis" class="hidden text-red-500 text-xs mt-1"></p>
                    </div>
                </div>

                {{-- Field: Isi Berita (TinyMCE) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Isi Berita</label>
                    <textarea id="isiBerita" name="konten" class="w-full border p-2 rounded transition"></textarea>
                    <p id="err-konten" class="hidden text-red-500 text-xs mt-1"></p>
                </div>

            </div>

            <div class="p-6 border-t flex justify-end gap-3 shrink-0 bg-white">
                <button type="button" onclick="toggleModal(false)"
                        class="px-5 py-2.5 text-gray-700 bg-gray-100 rounded hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="button" id="btnSimpanBerita"
                        onclick="submitBeritaAjax()"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center gap-2">
                    <span id="btnSimpanText">Simpan Berita</span>
                    {{-- Spinner muncul saat proses upload berlangsung --}}
                    <svg id="btnSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/enu1f2iy0sjymzrzspai0njbggo8727ajcedco8oks21ij3r/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // =========================================================
    // INISIALISASI TINYMCE — tidak ada perubahan dari sebelumnya
    // =========================================================
    tinymce.init({
        selector: '#isiBerita',
        menubar: false,
        plugins: 'lists link image table',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link',
        height: 250,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    // =========================================================
    // HELPER: Tampilkan atau sembunyikan pesan error per field
    // Dipanggil dengan fieldName sesuai key dari response JSON Laravel
    // =========================================================
    function tampilkanErrorField(fieldName, pesan) {
        const el = document.getElementById('err-' + fieldName);
        const input = document.querySelector('[name="' + fieldName + '"]');
        if (el) {
            el.textContent = pesan;
            el.classList.remove('hidden');
        }
        if (input) {
            input.classList.add('border-red-400');
        }
    }

    function bersihkanSemuaError() {
        // Sembunyikan kotak ringkasan error
        document.getElementById('ajaxErrorBox').classList.add('hidden');
        document.getElementById('ajaxErrorList').innerHTML = '';

        // Bersihkan semua field error satu per satu
        ['gambar', 'judul', 'kategori', 'status', 'tanggal', 'penulis', 'konten'].forEach(function(field) {
            const el = document.getElementById('err-' + field);
            const input = document.querySelector('[name="' + field + '"]');
            if (el) {
                el.textContent = '';
                el.classList.add('hidden');
            }
            if (input) {
                input.classList.remove('border-red-400');
            }
        });
    }

    // =========================================================
    // PREVIEW NAMA FILE GAMBAR
    // Menampilkan nama file yang dipilih agar user tahu fotonya
    // belum hilang walaupun error muncul
    // =========================================================
    document.getElementById('inputGambar').addEventListener('change', function() {
        const namaEl = document.getElementById('namaFileGambar');
        if (this.files && this.files[0]) {
            namaEl.textContent = '📎 File dipilih: ' + this.files[0].name;
            namaEl.classList.remove('hidden');
        } else {
            namaEl.classList.add('hidden');
        }
    });

    // =========================================================
    // FUNGSI UTAMA: Submit AJAX — menggantikan submit form biasa
    // Mengirim data ke server, menampilkan error inline jika gagal,
    // atau redirect jika berhasil — tanpa refresh halaman sama sekali
    // =========================================================
    function submitBeritaAjax() {
        // 1. Paksa TinyMCE sync kontennya ke textarea sebelum diambil
        if (typeof tinymce !== 'undefined' && tinymce.get('isiBerita')) {
            tinymce.get('isiBerita').save();
        }

        // 2. Bersihkan semua error lama dari submit sebelumnya
        bersihkanSemuaError();

        // 3. Validasi konten TinyMCE di sisi client — cegah kiriman kosong
        const isiKonten = tinymce.get('isiBerita') ? tinymce.get('isiBerita').getContent() : '';
        if (!isiKonten || isiKonten.trim() === '') {
            tampilkanErrorField('konten', 'Isi berita tidak boleh kosong.');
            return;
        }

        // 3b. Validasi client-side field teks lainnya sebelum dikirim ke server
        // Mencegah round-trip tidak perlu untuk input yang jelas kosong
        const form = document.getElementById('formBerita');
        let adaErrorClient = false;

        const judul = form.querySelector('[name="judul"]').value.trim();
        if (!judul) {
            tampilkanErrorField('judul', 'Judul berita tidak boleh kosong.');
            adaErrorClient = true;
        }

        const penulis = form.querySelector('[name="penulis"]').value.trim();
        if (!penulis) {
            tampilkanErrorField('penulis', 'Nama penulis tidak boleh kosong.');
            adaErrorClient = true;
        }

        const tanggal = form.querySelector('[name="tanggal"]').value;
        if (!tanggal) {
            tampilkanErrorField('tanggal', 'Tanggal rilis tidak boleh kosong.');
            adaErrorClient = true;
        }

        // Cek gambar hanya saat mode create (bukan edit — gambar boleh kosong saat update)
        const methodInput = document.getElementById('methodContainer').querySelector('input[name="_method"]');
        const isEditMode = methodInput && methodInput.value === 'PUT';
        const inputGambar = document.getElementById('inputGambar');
        if (!isEditMode && (!inputGambar.files || inputGambar.files.length === 0)) {
            tampilkanErrorField('gambar', 'Gambar thumbnail wajib diunggah.');
            adaErrorClient = true;
        }

        // Jika ada error client, scroll ke atas dan hentikan pengiriman
        if (adaErrorClient) {
            document.getElementById('ajaxErrorBox').classList.remove('hidden');
            document.getElementById('ajaxErrorList').innerHTML = '<li>Mohon lengkapi semua field yang wajib diisi.</li>';
            document.querySelector('#modal-tambah-berita .overflow-y-auto').scrollTop = 0;
            return;
        }
        // 4. Kumpulkan semua data form termasuk file gambar via FormData
        const formData = new FormData(form);

        // 5. Tampilkan spinner dan nonaktifkan tombol agar tidak double-submit
        const btnText = document.getElementById('btnSimpanText');
        const spinner = document.getElementById('btnSpinner');
        const btn = document.getElementById('btnSimpanBerita');
        btnText.textContent = 'Menyimpan...';
        spinner.classList.remove('hidden');
        btn.disabled = true;

        // 6. Kirim data ke server via fetch AJAX
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',  // penanda AJAX ke Laravel
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(function(response) {
            // Parse JSON apapun status code-nya (200 sukses, 422 error validasi)
            return response.json().then(function(data) {
                return { status: response.status, body: data };
            });
        })
        .then(function(result) {
            if (result.status === 200 && result.body.success) {
                // ✅ SUKSES: redirect ke URL yang dikirim server
                window.location.href = result.body.redirect;

            } else if (result.status === 422 && result.body.errors) {
                // ❌ ERROR VALIDASI: tampilkan error per field tanpa menutup modal

                const errors = result.body.errors;
                const errorList = document.getElementById('ajaxErrorList');

                // Tampilkan ringkasan di kotak atas
                document.getElementById('ajaxErrorBox').classList.remove('hidden');

                // Iterasi semua error dan tampilkan di field masing-masing
                Object.keys(errors).forEach(function(field) {
                    const pesan = errors[field][0]; // ambil pesan pertama per field
                    tampilkanErrorField(field, pesan);

                    // Tambahkan ke daftar ringkasan
                    const li = document.createElement('li');
                    li.textContent = pesan;
                    errorList.appendChild(li);
                });

                // Scroll ke atas konten modal agar error box terlihat
                document.querySelector('#modal-tambah-berita .overflow-y-auto').scrollTop = 0;

            } else {
                // Error tidak terduga dari server
                alert('Terjadi kesalahan pada server. Silakan coba lagi.');
            }
        })
        .catch(function(err) {
            alert('Gagal terhubung ke server. Periksa koneksi internet Anda.');
            console.error('AJAX Error:', err);
        })
        .finally(function() {
            // Kembalikan tombol ke kondisi normal setelah proses selesai
            btnText.textContent = 'Simpan Berita';
            spinner.classList.add('hidden');
            btn.disabled = false;
        });
    }
</script>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil - Smdaily Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex min-h-screen">

    @include('partials.admin.sidebar')

    <div class="flex-1 ml-0 md:ml-64 flex flex-col">
        @include('partials.admin.nav-admin')

        <main class="p-8">
            <div class="text-center mb-10">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2">Keamanan Akun</p>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Ganti Password</h1>
                <p class="text-gray-500">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun editorial Smdaily.</p>
            </div>

            <div class="max-w-md mx-auto">

                {{-- ✅ NOTIFIKASI SUKSES --}}
                @if(session('success'))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 mb-6 shadow-sm">
                    <i class="fas fa-circle-check text-emerald-500 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="font-semibold text-sm">Password Berhasil Diperbarui</p>
                        <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                {{-- ❌ NOTIFIKASI ERROR UMUM (selain field-spesifik) --}}
                @if($errors->has('loginError'))
                <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-5 py-4 mb-6 shadow-sm">
                    <i class="fas fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                    <p class="text-sm font-medium">{{ $errors->first('loginError') }}</p>
                </div>
                @endif

                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-lock text-blue-600 text-lg mr-2"></i>
                        <h2 class="text-lg font-bold text-gray-900">Ganti Password</h2>
                    </div>

                    <form action="{{ route('admin.update-password') }}" method="POST">
                        @csrf

                        {{-- Password Lama --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama</label>
                            <div class="relative w-full">
                                <input type="password" name="passLama" id="passLama"
                                       class="w-full border rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none pr-10
                                              {{ $errors->has('passLama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                                       placeholder="Masukkan password lama">
                                <i class="fas fa-eye-slash absolute right-3 top-3.5 text-gray-400 cursor-pointer hover:text-gray-600"
                                   onclick="togglePassword('passLama', this)"></i>
                            </div>
                            @error('passLama')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-triangle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <div class="relative w-full">
                                <input type="password" name="passBaru" id="passBaru"
                                       class="w-full border rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none pr-10
                                              {{ $errors->has('passBaru') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}"
                                       placeholder="Masukkan password baru (min. 8 karakter)">
                                <i class="fas fa-eye-slash absolute right-3 top-3.5 text-gray-400 cursor-pointer hover:text-gray-600"
                                   onclick="togglePassword('passBaru', this)"></i>
                            </div>
                            @error('passBaru')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                    <i class="fas fa-triangle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <div class="relative w-full">
                                <input type="password" name="passBaru_confirmation" id="passConfirm"
                                       class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none pr-10"
                                       placeholder="Ulangi password baru">
                                <i class="fas fa-eye-slash absolute right-3 top-3.5 text-gray-400 cursor-pointer hover:text-gray-600"
                                   onclick="togglePassword('passConfirm', this)"></i>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-all shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
    </script>
</body>
</html>
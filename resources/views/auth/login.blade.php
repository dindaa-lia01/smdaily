<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Smdaily</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'brand-dark': '#0B0A26', 
                        'brand-primary': '#4F46E5', 
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-indigo-50 font-sans text-gray-900 antialiased min-h-screen flex items-center justify-center p-4 lg:p-8">

    <div class="bg-white rounded-2xl shadow-xl flex flex-col md:flex-row w-full max-w-5xl overflow-hidden">
        
        <div class="relative p-8 lg:p-12 md:w-1/2 flex flex-col justify-center overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('assets/images/smd.jpg') }}" alt="Background Samarinda" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-brand-dark/80"></div>
            </div>

            <div class="relative z-10 text-white">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">
                    Platform Manajemen Berita <span class="text-indigo-300">Cerdas & Dinamis</span>
                </h1>
                <p class="text-gray-300 leading-relaxed text-sm lg:text-base pr-4">
                    Akses panel kontrol utama untuk mengelola konten editorial, statistik publikasi, dan performa tim redaksi dalam satu dashboard premium.
                </p>
            </div>
        </div>

        <div class="p-8 lg:p-12 md:w-1/2 flex flex-col justify-center bg-white relative">
            
            <div class="mb-6">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Smdaily Logo" class="h-10 w-auto">
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">Login Admin</h2>
            <p class="text-gray-500 mb-6 text-sm">Silakan masuk untuk mengelola portal berita Anda.</p>

            <form action="{{ url('/admin/login-proses') }}" method="POST" class="space-y-5">
                @csrf

                @error('loginError')
                <div class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ $message }}</span>
                </div>
                @enderror

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-brand-primary outline-none text-sm transition">
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative flex items-center">
                        <input type="password" name="password" id="password" required 
                               class="w-full px-4 py-2.5 pr-12 rounded-xl border border-gray-300 focus:ring-2 focus:ring-brand-primary outline-none text-sm transition">
                        
                        <button type="button" id="togglePassword" class="absolute right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5" id="eyeIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full bg-brand-primary text-white font-semibold py-3 px-4 rounded-xl hover:bg-opacity-90 transition text-sm shadow-lg shadow-indigo-100">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-400 font-medium tracking-wide">
                    © 2026 Smdaily. Dashboard Admin Eksklusif.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if(togglePassword) {
                togglePassword.addEventListener('click', function () {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        eyeIcon.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        `;
                    } else {
                        eyeIcon.innerHTML = `
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        `;
                    }
                });
            }
        });
    </script>
</body>
</html>
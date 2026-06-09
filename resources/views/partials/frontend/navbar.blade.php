{{-- 
    Navbar dibungkus <header> agar mobile-menu bisa keluar di bawah bar utama
    dengan benar menggunakan flex-col, tanpa mengacaukan layout halaman lain.
--}}
<header class="bg-white border-b border-gray-200 sticky top-0 z-50">

    {{-- ===== BAR UTAMA ===== --}}
    <nav class="max-w-[1536px] mx-auto px-6 py-3 flex items-center justify-between gap-4">

        {{-- Logo --}}
        <div class="flex-shrink-0">
            <a href="/"><img src="{{ asset('assets/images/logo.png') }}" alt="Smdaily Logo" class="h-10 md:h-11 w-auto"></a>
        </div>

        {{-- Nav links DESKTOP — tengah --}}
        <div class="hidden lg:flex items-center gap-6 xl:gap-8 flex-1 justify-center overflow-x-auto">
            <a href="{{ url('/') }}"
               class="whitespace-nowrap text-sm font-medium transition-all duration-300 border-b-2 pb-0.5
                      {{ request()->is('/') ? 'text-indigo-900 font-bold border-indigo-900' : 'text-gray-700 border-transparent hover:text-indigo-800 hover:border-indigo-800' }}">
                Beranda
            </a>
            @foreach($kategoriNavbar as $kat)
                <a href="{{ route('kategori.view', $kat->slug_kategori) }}"
                   class="whitespace-nowrap text-sm font-medium transition-all duration-300 border-b-2 pb-0.5
                          {{ request()->url() == route('kategori.view', $kat->slug_kategori) ? 'text-indigo-900 font-bold border-indigo-900' : 'text-gray-700 border-transparent hover:text-indigo-800 hover:border-indigo-800' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        </div>

        {{-- Search + Profile DESKTOP — kanan --}}
        <div class="hidden lg:flex items-center gap-4 flex-shrink-0">
            <form action="{{ route('berita.search') }}" method="GET" class="relative">
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                       placeholder="Cari berita..."
                       class="bg-gray-100 pl-9 pr-4 py-2 rounded-full text-sm w-44 xl:w-52 focus:outline-none focus:ring-2 focus:ring-indigo-200 transition">
                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </form>

            {{-- Dropdown Profile --}}
            <div class="relative">
                <button id="profile-trigger" class="text-gray-500 hover:text-indigo-800 focus:outline-none p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </button>
                <div id="profile-dropdown"
                     class="hidden absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-[9999]">
                    <a href="{{ url('/admin/login') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 rounded-xl">
                        Login Admin
                    </a>
                </div>
            </div>
        </div>

        {{-- Tombol MOBILE — search & hamburger --}}
        <div class="flex lg:hidden items-center gap-1">
            <button id="search-mobile-btn"
                    class="p-2 text-gray-600 hover:text-indigo-700 hover:bg-gray-100 rounded-lg transition"
                    aria-label="Cari berita">
                <i class="fas fa-search text-lg"></i>
            </button>
            <button id="hamburger-btn"
                    class="p-2 text-gray-600 hover:text-indigo-700 hover:bg-gray-100 rounded-lg transition"
                    aria-label="Buka menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

    </nav>

    {{-- 
        ===== SEARCH BAR MOBILE =====
        Berada di luar div flex utama agar turun ke bawah bar dengan benar
    --}}
    <div id="search-mobile-menu" class="hidden lg:hidden border-t border-gray-100 bg-white px-4 py-3">
        <form action="{{ route('berita.search') }}" method="GET" class="relative">
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                   placeholder="Cari berita..."
                   class="w-full bg-gray-100 pl-10 pr-4 py-2.5 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-200 transition">
            <button type="submit" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600">
                <i class="fas fa-search text-sm"></i>
            </button>
        </form>
    </div>

    {{-- 
        ===== MOBILE MENU (Hamburger) =====
        Berada di luar div flex utama agar turun ke bawah bar dengan benar
    --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-100 bg-white">
        <div class="px-6 py-4 space-y-1">
            <a href="{{ url('/') }}"
               class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->is('/') ? 'bg-indigo-50 text-indigo-900 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                Beranda
            </a>
            @foreach($kategoriNavbar as $kat)
                <a href="{{ route('kategori.view', $kat->slug_kategori) }}"
                   class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->url() == route('kategori.view', $kat->slug_kategori) ? 'bg-indigo-50 text-indigo-900 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        </div>
        <div class="px-6 py-3 border-t border-gray-100">
            <a href="{{ url('/admin/login') }}"
               class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-bold text-indigo-700 hover:bg-indigo-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Login Admin
            </a>
        </div>
    </div>

    <script>
        // Toggle hamburger menu
        document.getElementById('hamburger-btn').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
            document.getElementById('search-mobile-menu').classList.add('hidden');
        });

        // Toggle search mobile
        document.getElementById('search-mobile-btn').addEventListener('click', function () {
            document.getElementById('search-mobile-menu').classList.toggle('hidden');
            document.getElementById('mobile-menu').classList.add('hidden');
            // Fokus otomatis ke input search saat dibuka
            const searchInput = document.querySelector('#search-mobile-menu input');
            if (searchInput && !document.getElementById('search-mobile-menu').classList.contains('hidden')) {
                setTimeout(() => searchInput.focus(), 50);
            }
        });

        // Dropdown profile desktop
        const profBtn = document.getElementById('profile-trigger');
        const profDrop = document.getElementById('profile-dropdown');
        if (profBtn) {
            profBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                profDrop.classList.toggle('hidden');
            });
            window.addEventListener('click', function () {
                profDrop.classList.add('hidden');
            });
        }
    </script>

</header>
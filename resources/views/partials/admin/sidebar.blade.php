<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="p-6 flex justify-between items-center">
        <div>
            <img src="{{ asset('assets/images/logo.png') }}" alt="Smdaily Logo" class="h-8 w-auto mb-1">
            <p class="text-xs text-gray-400">News Management Console</p>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-gray-500 text-xl">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="/admin/dashboard" class="flex items-center px-6 py-3 {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-700 hover:bg-gray-100' }} transition rounded-lg">
            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
        </a>

        <a href="/admin/man-berita" class="flex items-center px-6 py-3 {{ request()->is('admin/man-berita') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-700 hover:bg-gray-100' }} transition rounded-lg">
            <i class="fas fa-newspaper mr-3"></i> Manajemen Berita
        </a>

        <a href="/admin/kategori" class="flex items-center px-6 py-3 {{ request()->is('admin/kategori') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-700 hover:bg-gray-100' }} transition rounded-lg">
            <i class="fas fa-tags mr-3"></i> Manajemen Kategori
        </a>

        <a href="/admin/profil" class="flex items-center px-6 py-3 {{ request()->is('admin/profil') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-700 hover:bg-gray-100' }} transition rounded-lg">
            <i class="fas fa-user mr-3"></i> Profil Saya
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-xl">
            <img class="w-10 h-10 rounded-full object-cover" src="https://i.pravatar.cc/100?img=33" alt="Admin">
            <div>
                <h4 class="text-sm font-bold">Admin Utama</h4>
                <span class="text-[10px] text-gray-500">SUPER ADMIN</span>
            </div>
        </div>
    </div>
</aside>

<div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>
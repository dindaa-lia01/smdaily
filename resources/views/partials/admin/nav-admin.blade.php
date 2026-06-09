<header class="bg-white border-b border-gray-200 h-20 px-4 md:px-8 flex items-center justify-between sticky top-0 z-30">
    <div class="flex items-center">
        <button onclick="toggleSidebar()" class="md:hidden text-gray-600 text-xl mr-4 p-2 hover:bg-gray-100 rounded-lg">
            <i class="fas fa-bars"></i>
        </button>
        
        <img src="{{ asset('assets/images/logo.png') }}" alt="Smdaily Logo" class="h-8 w-auto">
    </div>
    
    <div class="flex items-center space-x-4">
        <a href="/" class="text-sm font-medium text-gray-600 hover:text-blue-600">
            <i class="fas fa-globe mr-1"></i> Lihat Website
        </a>
    </div>
</header>
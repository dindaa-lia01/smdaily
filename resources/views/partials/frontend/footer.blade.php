<footer class="w-full bg-white border-t border-gray-200 mt-16">
    <div class="max-w-[1536px] mx-auto px-4 lg:px-16 py-8">
        <div class="flex flex-col items-center gap-8">
            
            <a href="/">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Smdaily Logo" class="h-8 md:h-10 w-auto">
            </a>

            <nav class="flex flex-wrap justify-center gap-x-6 gap-y-3 text-center">
                <a href="#" class="text-xs md:text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Tentang Kami</a>
                <a href="#" class="text-xs md:text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="text-xs md:text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="text-xs md:text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-colors">Kontak</a>
            </nav>

            <div class="text-xs md:text-sm font-medium text-gray-500 text-center">
                &copy; {{ date('Y') }} Smdaily. Energi Positif untuk Indonesia.
            </div>
        </div>
    </div>
</footer>
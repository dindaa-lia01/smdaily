<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smdaily - Energi Positif untuk Indonesia</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#4F46E5', // Purplish-blue dari logo & badge
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white font-sans text-gray-900 antialiased">

    @include('partials.frontend.navbar')

    <!-- 1. BREAKING NEWS -->
    <div class="bg-white border-b border-gray-100 py-2.5 overflow-hidden">
    <div class="max-w-[1536px] mx-auto px-6 lg:px-16 flex items-center">
        <span class="bg-indigo-900 text-white text-[9px] font-bold px-2.5 py-0.5 rounded-sm uppercase tracking-wider mr-4 shrink-0 shadow-sm">
            Breaking News
        </span>
        
        <div class="relative flex-1 overflow-hidden">
            <marquee scrollamount="5" class="text-sm font-medium text-gray-900">
                @if(isset($breakingNews))
                    @foreach($breakingNews as $b) 
                        <span class="inline-flex items-center">
                            <span class="hover:text-indigo-700 transition cursor-pointer">
                                {{ $b->judul_berita }}
                            </span>
                            <span class="text-indigo-400 mx-4">•</span>
                        </span>
                    @endforeach
                @endif
            </marquee>
        </div>
    </div>
</div>

    <main class="w-full">
        @yield('content')
    </main>

    @include('partials.frontend.footer')

    <script src="{{ asset('assets/js/slider.js') }}"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const profileBtn = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileDropdown');

        if (profileBtn && profileMenu) {
            // Toggle menu saat tombol diklik
            profileBtn.addEventListener('click', function (e) {
              e.stopPropagation(); // Mencegah klik tembus ke window
              profileMenu.classList.toggle('hidden');
            });

            // Tutup menu jika user mengklik area lain di luar dropdown
            window.addEventListener('click', function (e) {
              if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                if (!profileMenu.classList.contains('hidden')) {
                  profileMenu.classList.add('hidden');
                }
              }
            });
        }
      });
    </script>



</body>
</html>
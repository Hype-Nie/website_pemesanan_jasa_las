<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Bengkel Asyraf - Jasa Las Talaga Majalengka')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="bengkel las, jasa las, talaga, majalengka" name="keywords">
    <meta content="Bengkel Asyraf - Jasa Las Terbaik di Talaga, Majalengka" name="description">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('weldork-css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('weldork-css/style.css') }}?v={{ filemtime(public_path('weldork-css/style.css')) }}" rel="stylesheet">

    @stack('styles')
    <style>
        .transition-fade {
            transition: 0.3s;
            opacity: 1;
        }
        html.is-animating .transition-fade {
            opacity: 0;
        }
    </style>
</head>

<body>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    @include('components.topbar')
    <!-- Topbar End -->

    <!-- Navbar Start -->
    @include('components.navbar')
    <!-- Navbar End -->

    <!-- Content -->
    <main id="swup" class="transition-fade">
        @yield('content')
    </main>

    <!-- Footer Start -->
    @include('components.footer')
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('weldork-js/main.js') }}"></script>
    <script src="https://unpkg.com/swup@4"></script>
    <script>
        const swup = new Swup({
            containers: ['#swup']
        });
        swup.hooks.on('page:view', () => {
            if(typeof initApp === 'function') {
                initApp();
            }

            // Update Navbar Active State
            const currentPath = window.location.pathname;
            document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if(!href) return;
                
                try {
                    const linkPath = new URL(href, window.location.origin).pathname;
                    if (currentPath === '/' && linkPath === '/') {
                        link.classList.add('active');
                    } else if (currentPath !== '/' && linkPath !== '/' && currentPath.startsWith(linkPath)) {
                        link.classList.add('active');
                    }
                } catch(e) {}
            });
        });
    </script>

    @stack('scripts')
</body>

</html>

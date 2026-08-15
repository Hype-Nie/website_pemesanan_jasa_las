<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Bengkel Asyraf - Jasa Las Talaga Bone')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="bengkel las, jasa las, talaga, bone" name="keywords">
    <meta content="Bengkel Asyraf - Jasa Las Terbaik di Talaga, Bone" name="description">

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
    <link href="{{ asset('weldork-css/bootstrap.min.css') }}?v={{ time() }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('weldork-css/style.css') }}?v={{ time() }}" rel="stylesheet">

    @stack('styles')

        <style>
        /* Paksaan warna text pada tombol utama dan efek hover */
        .btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-check:checked+.btn-primary {
            color: #ffffff !important;
        }
        .btn-secondary, .btn-secondary:hover, .btn-secondary:focus, .btn-secondary:active, .btn-check:checked+.btn-secondary {
            color: #000000 !important;
        }
        .btn-outline-primary:hover, .btn-outline-primary:active, .btn-check:checked+.btn-outline-primary {
            color: #ffffff !important;
        }
        .btn-outline-secondary:hover, .btn-outline-secondary:active, .btn-check:checked+.btn-outline-secondary {
            color: #000000 !important;
        }

        /* Fix Form input (Admin/Dashboard) supaya text tidak kuning */
        .form-control, .form-select, .form-control:focus, .form-select:focus {
            color: #1b1b18 !important;
        }

        /* Paksaan agar saat item di-hover, text di dalamnya jadi putih (untuk service-item dll) */
        .service .service-item:hover h1, .service .service-item:hover h2, .service .service-item:hover h3, 
        .service .service-item:hover h4, .service .service-item:hover h5, .service .service-item:hover h6, 
        .service .service-item:hover p, .service .service-item:hover span, .service .service-item:hover div, 
        .service .service-item:hover a:not(.btn-light):not(.bg-primary) {
            color: #ffffff !important;
        }
        .service .service-item:hover .bg-primary, .service .service-item:hover .bg-primary i {
            background-color: #ffffff !important;
            color: #1B2538 !important;
        }

        /* Fix Footer hover links supaya terlihat di background gelap */
        .footer .btn.btn-link:hover, .copyright a:hover, .footer a:hover {
            color: #FACC15 !important;
            letter-spacing: 1px;
        }
        
        /* Fix Footer text abu-abu agar lebih terang dan terbaca */
        .footer p, .footer span, .footer div, .copyright {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* Active tab indicator di Navbar supaya jelas berada di mana */
        .navbar-light .navbar-nav .nav-link {
            position: relative;
        }
        .navbar-light .navbar-nav .nav-link.active,
        .navbar-light .navbar-nav .nav-link:hover {
            color: #FACC15 !important;
            font-weight: 700 !important;
            text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.1);
        }
        .navbar-light .navbar-nav .nav-link.active::after,
        .navbar-light .navbar-nav .nav-link:hover::after {
            content: '';
            display: block;
            width: 80%;
            height: 3px;
            background-color: #FACC15;
            position: absolute;
            bottom: 0px;
            left: 10%;
            border-radius: 2px;
        }
    </style>
</head>


<body>
    <!-- Spinner Start -->
    @include('components.spinner')
    <!-- Spinner End -->

    <!-- Topbar Start -->
    @include('components.topbar')
    <!-- Topbar End -->

    <!-- Navbar Start -->
    @include('components.navbar')
    <!-- Navbar End -->

    <!-- Content -->
    <main id="page-content">
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

    <!-- AJAX SPA Navigation -->
    <script>
    (function ($) {
        'use strict';

        var $content   = $('#page-content');
        var isLoading  = false;

        /** Mulai progress bar bawaan template */
        function startProgress() {
            $('#spinner').addClass('show');
        }

        /** Selesaikan progress bar */
        function finishProgress() {
            // Main.js initApp() otomatis mematikan spinner, 
            // tapi kita pastikan mati jika delay.
            setTimeout(function () {
                $('#spinner').removeClass('show');
            }, 100);
        }

        /** Update state active navbar */
        function updateNavActive(path) {
            $('.navbar-nav .nav-link').each(function () {
                var href = $(this).attr('href');
                if (!href) return;
                try {
                    var linkPath = new URL(href, window.location.origin).pathname;
                    if ((path === '/' && linkPath === '/') ||
                        (path !== '/' && linkPath !== '/' && path.startsWith(linkPath))) {
                        $(this).addClass('active');
                    } else {
                        $(this).removeClass('active');
                    }
                } catch (e) {}
            });
        }

        /** Navigasi AJAX ke URL tertentu */
        function navigate(url, pushState) {
            if (isLoading) return;
            isLoading = true;

            startProgress();
            var startTime = Date.now();

            $.ajax({
                url: url,
                type: 'GET',
                success: function (html) {
                    var elapsed = Date.now() - startTime;
                    // Pastikan minimal 250ms berlalu agar animasi fade-out CSS selesai sebelum HTML diganti
                    var delay = Math.max(0, 250 - elapsed);

                    setTimeout(function () {
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        var newContent = doc.querySelector('#page-content');
                        var newTitle = doc.title || document.title;

                        if (newContent) {
                            $content.html(newContent.innerHTML);
                        }

                        // Update title
                        document.title = newTitle;

                        // Update URL di address bar
                        if (pushState !== false) {
                            history.pushState({ url: url }, newTitle, url);
                        }

                        // Scroll ke atas dengan instant agar tidak bentrok dengan WOW.js
                        window.scrollTo({ top: 0, behavior: 'instant' });

                        // Update nav active state
                        var path = new URL(url, window.location.origin).pathname;
                        updateNavActive(path);

                        // Re-init semua jQuery plugins
                        if (typeof initApp === 'function') {
                            initApp();
                        }

                        finishProgress();
                        isLoading = false;
                    }, delay);
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr);
                    finishProgress();
                    isLoading = false;
                    window.location.href = url;
                }
            });
        }

        /** Intercept semua klik link internal */
        $(document).on('click', 'a', function (e) {
            var $link = $(this);
            var href = $link.attr('href');

            // Skip invalid, anchor, blank, download
            if (!href || href === '#' || href.startsWith('#') ||
                $link.attr('target') === '_blank' ||
                $link.attr('download') !== undefined) {
                return;
            }

            // Skip form submissions (like logout)
            if ($link.closest('form').length) return;

            var linkUrl;
            try {
                linkUrl = new URL(href, window.location.href);
            } catch (err) {
                return;
            }

            // Skip link eksternal (beda domain/host)
            if (linkUrl.hostname !== window.location.hostname) {
                return;
            }

            // Skip link ke admin panel dari frontend
            if (linkUrl.pathname.startsWith('/admin')) {
                return;
            }

            // Skip jika URL sama persis dengan halaman saat ini
            if (linkUrl.pathname === window.location.pathname && linkUrl.search === window.location.search) {
                e.preventDefault(); // cegah reload
                return;
            }

            e.preventDefault();
            // Gunakan path relative untuk AJAX agar terhindar dari isu CORS / Mixed Content HTTP vs HTTPS
            var relativeUrl = linkUrl.pathname + linkUrl.search;
            navigate(relativeUrl);
        });

        /** Handle tombol Back / Forward browser */
        $(window).on('popstate', function (e) {
            var state = e.originalEvent.state;
            var url = (state && state.url) ? state.url : window.location.href;
            navigate(url, false);
        });

        // Simpan state halaman pertama
        history.replaceState({ url: window.location.href }, document.title, window.location.href);

        // Set active nav on first load
        updateNavActive(window.location.pathname);

    })(jQuery);
    </script>

    @stack('scripts')
</body>

</html>

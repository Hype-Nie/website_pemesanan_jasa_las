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

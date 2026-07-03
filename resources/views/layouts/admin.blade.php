<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin - Bengkel Asyraf')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

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
            color: #495057 !important;
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

    <link href="{{ asset('weldork-css/bootstrap.min.css') }}?v={{ filemtime(public_path('weldork-css/bootstrap.min.css')) }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1a1a2e;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .admin-sidebar .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar .sidebar-brand h4 {
            color: #fff;
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 0.85rem 1.25rem;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            border-left-color: #FD5D14;
        }

        .admin-sidebar .nav-link i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }

        .admin-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .admin-topnav {
            background: #fff;
            padding: 0.75rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .admin-main {
            padding: 1.5rem;
        }

        .stat-card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: -260px;
            }

            .admin-sidebar.show {
                margin-left: 0;
            }

            .admin-content {
                margin-left: 0;
            }
        }
    </style>

    <style>
        /* AJAX progress bar */
        #ajax-progress {
            position: fixed; top: 0; left: 0; height: 3px; width: 0%;
            background: linear-gradient(90deg, #FD5D14, #ff8c5a);
            z-index: 99999; transition: width 0.3s ease, opacity 0.3s ease; opacity: 0;
        }
        #ajax-progress.active { opacity: 1; }
        #page-content { opacity: 1; transition: opacity 0.2s ease; }
        #page-content.ajax-loading { opacity: 0; }
    </style>
    @stack('styles')
</head>

<body>
    <div id="ajax-progress"></div>

    <!-- Sidebar -->
    @include('components.admin.sidebar')

    <!-- Content Wrapper -->
    <div class="admin-content">
        <!-- Top Navbar -->
        @include('components.admin.topnav')

        <!-- Main Content -->
        <div class="admin-main" id="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
            
            <!-- Scripts spesifik halaman ikut dimuat saat AJAX -->
            @stack('scripts')
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        });

        // AJAX SPA Admin Navigation
        (function ($) {
            var $progress = $('#ajax-progress');
            var $content = $('#page-content');
            var isLoading = false;

            function startProgress() {
                $progress.stop(true).css({ width: '0%', opacity: 1 }).addClass('active').animate({ width: '75%' }, 400);
            }
            function finishProgress() {
                $progress.animate({ width: '100%' }, 200, function () {
                    $(this).fadeOut(200, function () { $(this).css('width', '0%').removeClass('active'); });
                });
            }

            function updateNavActive(path) {
                $('.admin-sidebar .nav-link').each(function () {
                    var href = $(this).attr('href');
                    if (!href) return;
                    try {
                        var linkPath = new URL(href, window.location.origin).pathname;
                        if (path.startsWith(linkPath)) {
                            $(this).addClass('active');
                        } else {
                            $(this).removeClass('active');
                        }
                    } catch (e) {}
                });
            }

            function navigate(url, pushState) {
                if (isLoading) return;
                isLoading = true;
                startProgress();
                $content.addClass('ajax-loading');
                var startTime = Date.now();

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (html) {
                        var delay = Math.max(0, 200 - (Date.now() - startTime));
                        setTimeout(function() {
                            var parser = new DOMParser();
                            var doc = parser.parseFromString(html, 'text/html');
                            var newContent = doc.querySelector('#page-content');
                            
                            if (newContent) {
                                // Extract scripts to execute them
                                $content.html(newContent.innerHTML);
                            }

                            document.title = doc.title || document.title;
                            if (pushState !== false) history.pushState({ url: url }, document.title, url);
                            window.scrollTo({ top: 0, behavior: 'instant' });

                            var path = new URL(url, window.location.origin).pathname;
                            updateNavActive(path);

                            finishProgress();
                            $content.removeClass('ajax-loading');
                            isLoading = false;
                        }, delay);
                    },
                    error: function () {
                        finishProgress();
                        window.location.href = url;
                    }
                });
            }

            $(document).on('click', 'a.nav-link, a.btn', function (e) {
                var $link = $(this);
                var href = $link.attr('href');
                if (!href || href === '#' || href.startsWith('#') || $link.attr('target') === '_blank' || $link.attr('download') !== undefined) return;
                if ($link.closest('form').length) return;

                var linkUrl;
                try { linkUrl = new URL(href, window.location.href); } catch (err) { return; }
                if (linkUrl.hostname !== window.location.hostname) return;
                // Only intercept admin links
                if (!linkUrl.pathname.startsWith('/admin')) return;
                if (linkUrl.pathname === window.location.pathname && linkUrl.search === window.location.search) {
                    e.preventDefault(); return;
                }
                e.preventDefault();
                navigate(linkUrl.pathname + linkUrl.search);
            });

            $(window).on('popstate', function (e) {
                var state = e.originalEvent.state;
                if (state && state.url) navigate(state.url, false);
            });

            history.replaceState({ url: window.location.href }, document.title, window.location.href);
            updateNavActive(window.location.pathname);
        })(jQuery);
    </script>
</body>

</html>

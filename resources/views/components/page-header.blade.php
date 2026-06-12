@props(['title', 'breadcrumbs' => []])

<div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center py-5">
        <h1 class="display-4 text-white text-uppercase mb-3 animated slideInDown">{{ $title }}</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center text-uppercase mb-0">
                <li class="breadcrumb-item"><a class="text-white" href="{{ route('home') }}">Beranda</a></li>
                @foreach($breadcrumbs as $label => $url)
                    @if($url)
                        <li class="breadcrumb-item"><a class="text-white" href="{{ $url }}">{{ $label }}</a></li>
                    @else
                        <li class="breadcrumb-item text-primary active" aria-current="page">{{ $label }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
</div>

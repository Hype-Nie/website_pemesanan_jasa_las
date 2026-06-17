<!-- Footer Start -->
<div class="container-fluid bg-dark footer py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase text-light mb-4">Bengkel Asyraf</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>Talaga, Majalengka, Jawa Barat</p>
                <p class="mb-2"><i class="fa fa-phone-alt text-primary me-3"></i>+62 812-XXXX-XXXX</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary me-3"></i>info@bengkelasyraf.com</p>
                <div class="d-flex pt-3">
                    <a class="btn btn-square btn-light me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-square btn-light me-2" href="#"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-square btn-light me-2" href="https://wa.me/62812XXXXXXXX"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase text-light mb-4">Tautan Cepat</h5>
                <a class="btn btn-link" href="{{ route('home') }}">Beranda</a>
                <a class="btn btn-link" href="{{ route('catalog.index') }}">Katalog Produk</a>
                <a class="btn btn-link" href="{{ route('customer.orders.create') }}">Pesan Custom</a>
                <a class="btn btn-link" href="{{ route('orders.track') }}">Lacak Pesanan</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase text-light mb-4">Jam Operasional</h5>
                <p class="text-uppercase mb-0">Senin - Sabtu</p>
                <p>08:00 - 17:00 WIB</p>
                <p class="text-uppercase mb-0">Minggu</p>
                <p>Tutup</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-uppercase text-light mb-4">Galeri</h5>
                <div class="row g-1">
                    <div class="col-4">
                        <img class="img-fluid" src="{{ asset('img/service-1.jpg') }}" alt="Galeri Las 1">
                    </div>
                    <div class="col-4">
                        <img class="img-fluid" src="{{ asset('img/service-2.jpg') }}" alt="Galeri Las 2">
                    </div>
                    <div class="col-4">
                        <img class="img-fluid" src="{{ asset('img/service-3.jpg') }}" alt="Galeri Las 3">
                    </div>
                    <div class="col-4">
                        <img class="img-fluid" src="{{ asset('img/service-4.jpg') }}" alt="Galeri Las 4">
                    </div>
                    <div class="col-4">
                        <img class="img-fluid" src="{{ asset('img/service-5.jpg') }}" alt="Galeri Las 5">
                    </div>
                    <div class="col-4">
                        <img class="img-fluid" src="{{ asset('img/service-6.jpg') }}" alt="Galeri Las 6">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid text-body copyright py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; {{ date('Y') }} <a class="fw-semi-bold" href="{{ route('home') }}">Bengkel Asyraf</a>, Hak Cipta Dilindungi.
            </div>
            <div class="col-md-6 text-center text-md-end">
                Jasa Las Terbaik di Talaga, Majalengka
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->

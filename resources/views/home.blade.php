@extends('layouts.app')

@section('title', 'Bengkel Asyraf - Jasa Las Terbaik di Talaga Bone')

@section('content')
    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-6 wow fadeIn" data-wow-delay="0.1s">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1">
                    <img class="img-fluid" src="{{ asset('img/carousel-1.jpg') }}" alt="Slide 1">
                </button>
                <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="1" aria-label="Slide 2">
                    <img class="img-fluid" src="{{ asset('img/carousel-2.jpg') }}" alt="Slide 2">
                </button>
                <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="2" aria-label="Slide 3">
                    <img class="img-fluid" src="{{ asset('img/carousel-3.jpg') }}" alt="Slide 3">
                </button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="{{ asset('img/carousel-1.jpg') }}" alt="Bengkel Las Terbaik">
                    <div class="carousel-caption">
                        <h1 class="display-1 text-uppercase text-white mb-4 animated zoomIn">Bengkel Las Terbaik di Talaga</h1>
                        <a href="{{ route('catalog.index') }}" class="btn btn-primary py-3 px-4">Lihat Katalog</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="{{ asset('img/carousel-2.jpg') }}" alt="Solusi Logam Custom">
                    <div class="carousel-caption">
                        <h1 class="display-1 text-uppercase text-white mb-4 animated zoomIn">Solusi Logam Custom Berkualitas</h1>
                        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary py-3 px-4">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="{{ asset('img/carousel-3.jpg') }}" alt="Pengerjaan Cepat">
                    <div class="carousel-caption">
                        <h1 class="display-1 text-uppercase text-white mb-4 animated zoomIn">Pengerjaan Cepat & Terpercaya</h1>
                        <a href="{{ route('orders.track') }}" class="btn btn-primary py-3 px-4">Lacak Pesanan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- About Start -->
    <div class="container-fluid pt-6 pb-6">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img">
                        <img class="img-fluid w-100" src="{{ asset('img/about.jpg') }}" alt="Tentang Bengkel Asyraf">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-6 text-uppercase mb-4">Bengkel Las Profesional & Berkualitas</h1>
                    <p class="mb-4">Bengkel Asyraf adalah bengkel las terpercaya di Talaga, Bone, Sulawesi Selatan. Kami menyediakan berbagai jasa pengelasan dan pembuatan produk logam custom sesuai kebutuhan Anda.
                        Dengan pengalaman bertahun-tahun dan tukang las berpengalaman, kami siap memberikan hasil terbaik.</p>
                    <div class="row g-5 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 btn-xl-square bg-light me-3">
                                    <i class="fa fa-users-cog fa-2x text-primary"></i>
                                </div>
                                <h5 class="lh-base text-uppercase mb-0">Tukang Las Berpengalaman</h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 btn-xl-square bg-light me-3">
                                    <i class="fa fa-tachometer-alt fa-2x text-primary"></i>
                                </div>
                                <h5 class="lh-base text-uppercase mb-0">Pengerjaan Cepat & Tepat</h5>
                            </div>
                        </div>
                    </div>
                    <p><i class="fa fa-check-square text-primary me-3"></i>Pagar, Kanopi, Teralis, dan Railing Custom</p>
                    <p><i class="fa fa-check-square text-primary me-3"></i>Material besi dan baja berkualitas tinggi</p>
                    <p><i class="fa fa-check-square text-primary me-3"></i>Garansi pengerjaan dan kepuasan pelanggan</p>
                    <div class="border border-5 border-primary p-4 text-center mt-4">
                        <h4 class="lh-base text-uppercase mb-0">Solusi Terbaik untuk Semua Kebutuhan Las & Logam Anda</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Features Start -->
    <div class="container-fluid pt-6 pb-6">
        <div class="container pt-4">
            <div class="row g-0 feature-row wow fadeIn" data-wow-delay="0.1s">
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="feature-item border h-100">
                        <div class="feature-icon btn-xxl-square bg-primary mb-4 mt-n4">
                            <i class="fa fa-hammer fa-2x text-white"></i>
                        </div>
                        <div class="p-5 pt-0">
                            <h5 class="text-uppercase mb-3">Las Berkualitas</h5>
                            <p>Hasil pengelasan rapi, kuat, dan tahan lama menggunakan teknik las terbaik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.4s">
                    <div class="feature-item border h-100">
                        <div class="feature-icon btn-xxl-square bg-primary mb-4 mt-n4">
                            <i class="fa fa-dollar-sign fa-2x text-white"></i>
                        </div>
                        <div class="p-5 pt-0">
                            <h5 class="text-uppercase">Harga Terjangkau</h5>
                            <p>Harga bersaing dengan kualitas premium. Tersedia konsultasi gratis untuk estimasi biaya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                    <div class="feature-item border h-100">
                        <div class="feature-icon btn-xxl-square bg-primary mb-4 mt-n4">
                            <i class="fa fa-check-double fa-2x text-white"></i>
                        </div>
                        <div class="p-5 pt-0">
                            <h5 class="text-uppercase">Tukang Terbaik</h5>
                            <p>Tim tukang las berpengalaman yang telah menangani ratusan proyek di Majalengka.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.6s">
                    <div class="feature-item border h-100">
                        <div class="feature-icon btn-xxl-square bg-primary mb-4 mt-n4">
                            <i class="fa fa-tools fa-2x text-white"></i>
                        </div>
                        <div class="p-5 pt-0">
                            <h5 class="text-uppercase">Material Kuat</h5>
                            <p>Menggunakan material besi dan baja pilihan untuk hasil yang kokoh dan awet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->


    <!-- Why Choose Us Start -->
    <div class="container-fluid feature mt-6 mb-6 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-0 justify-content-end">
                <div class="col-lg-6 pt-5">
                    <div class="mt-5">
                        <h1 class="display-6 text-white text-uppercase mb-4 wow fadeIn" data-wow-delay="0.3s">Mengapa Memilih Bengkel Asyraf?</h1>
                        <p class="text-light mb-4 wow fadeIn" data-wow-delay="0.4s">Bengkel Asyraf telah melayani masyarakat Talaga dan sekitarnya dengan jasa las berkualitas. Kepuasan pelanggan adalah prioritas utama kami.</p>
                        <div class="row g-4 pt-2 mb-4">
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.4s">
                                <div class="flex-column text-center border border-5 border-primary p-5">
                                    <h1 class="text-white" data-toggle="counter-up">500</h1>
                                    <p class="text-white text-uppercase mb-0">Pelanggan Puas</p>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="flex-column text-center border border-5 border-primary p-5">
                                    <h1 class="text-white" data-toggle="counter-up">750</h1>
                                    <p class="text-white text-uppercase mb-0">Proyek Selesai</p>
                                </div>
                            </div>
                        </div>
                        <div class="border border-5 border-primary border-bottom-0 p-5">
                            <div class="experience mb-4 wow fadeIn" data-wow-delay="0.6s">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-white text-uppercase">Pengalaman</span>
                                    <span class="text-white">90%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="experience wow fadeIn" data-wow-delay="0.7s">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-white text-uppercase">Kepuasan Pelanggan</span>
                                    <span class="text-white">95%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us End -->


    <!-- Services/Products Start -->
    <div class="container-fluid service pt-6 pb-6">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="display-6 text-uppercase mb-5">Produk & Layanan Kami</h1>
            </div>
            <div class="row g-4">
                @forelse($products ?? [] as $index => $product)
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.1 }}s">
                        <div class="service-item">
                            <div class="service-inner pb-5">
                                @if($product->image_path)
                                    <img class="img-fluid w-100" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
                                @else
                                    <img class="img-fluid w-100" src="{{ asset('img/service-' . (($index % 8) + 1) . '.jpg') }}" alt="{{ $product->name }}">
                                @endif
                                <div class="service-text px-5 pt-4">
                                    <h5 class="text-uppercase">{{ $product->name }}</h5>
                                    <p>{{ Str::limit($product->description, 80) }}</p>
                                    <p class="text-primary fw-bold">Rp {{ number_format($product->price_estimate, 0, ',', '.') }}</p>
                                </div>
                                <a class="btn btn-light px-3" href="{{ route('catalog.show', $product->id) }}">Detail<i class="bi bi-chevron-double-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Fallback static services when no products exist --}}
                    @foreach(['Pagar Besi', 'Kanopi', 'Teralis Jendela', 'Railing Tangga', 'Pintu Besi', 'Pagar Minimalis', 'Kanopi Custom', 'Teralis Custom'] as $index => $serviceName)
                        <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index % 4) * 0.1 }}s">
                            <div class="service-item">
                                <div class="service-inner pb-5">
                                    <img class="img-fluid w-100" src="{{ asset('img/service-' . ($index + 1) . '.jpg') }}" alt="{{ $serviceName }}">
                                    <div class="service-text px-5 pt-4">
                                        <h5 class="text-uppercase">{{ $serviceName }}</h5>
                                        <p>Pembuatan {{ strtolower($serviceName) }} custom berkualitas tinggi dengan material pilihan.</p>
                                    </div>
                                    <a class="btn btn-light px-3" href="{{ route('catalog.index') }}">Lihat Katalog<i class="bi bi-chevron-double-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </div>
    <!-- Services/Products End -->


    <!-- CTA / Appointment Start -->
    <div class="container-fluid appoinment mt-6 mb-6 py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container pt-5">
            <div class="row gy-5 gx-0">
                <div class="col-lg-6 pe-lg-5 wow fadeIn" data-wow-delay="0.3s">
                    <h1 class="display-6 text-uppercase text-white mb-4">Siap Memesan? Hubungi Kami Sekarang!</h1>
                    <p class="text-white mb-5 wow fadeIn" data-wow-delay="0.4s">Kami siap membantu mewujudkan kebutuhan pagar, kanopi, teralis, dan produk las lainnya. Konsultasi gratis dan estimasi harga transparan.</p>
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.5s">
                        <div class="btn-lg-square bg-white">
                            <i class="bi bi-geo-alt text-dark fs-3"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-white text-uppercase">Alamat Bengkel</h6>
                            <span class="text-white">Talaga, Bone, Sulawesi Selatan</span>
                        </div>
                    </div>
                    <hr class="bg-body">
                    <div class="d-flex align-items-start wow fadeIn" data-wow-delay="0.6s">
                        <div class="btn-lg-square bg-white">
                            <i class="bi bi-clock text-dark fs-3"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-white text-uppercase">Jam Operasional</h6>
                            <span class="text-white">Senin-Sabtu 08:00-17:00, Minggu Tutup</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-n5 wow fadeIn" data-wow-delay="0.7s">
                    <div class="bg-white p-5 text-center">
                        <h2 class="text-uppercase mb-4">Pesan Sekarang</h2>
                        <p class="mb-4">Buat pesanan custom sesuai kebutuhan Anda melalui formulir pemesanan online kami.</p>
                        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-clipboard-list me-2"></i>Buat Pesanan
                        </a>
                        <hr>
                        <p class="mb-3">Atau lacak pesanan yang sudah ada:</p>
                        <a href="{{ route('orders.track') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-search me-2"></i>Lacak Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CTA / Appointment End -->


    <!-- Testimonial Start -->
    <div class="container-fluid pt-6 pb-6">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="display-6 text-uppercase mb-5">Apa Kata Pelanggan Kami</h1>
            </div>
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="testimonial-img">
                        <div class="animated flip infinite">
                            <img class="img-fluid" src="{{ asset('img/testimonial-1.jpg') }}" alt="Testimoni">
                        </div>
                        <div class="animated flip infinite">
                            <img class="img-fluid" src="{{ asset('img/testimonial-2.jpg') }}" alt="Testimoni">
                        </div>
                        <div class="animated flip infinite">
                            <img class="img-fluid" src="{{ asset('img/testimonial-3.jpg') }}" alt="Testimoni">
                        </div>
                        <div class="animated flip infinite">
                            <img class="img-fluid" src="{{ asset('img/testimonial-4.jpg') }}" alt="Testimoni">
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="owl-carousel testimonial-carousel">
                        <div class="testimonial-item">
                            <div class="d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('img/testimonial-1.jpg') }}" alt="Ahmad Sudrajat">
                                <div class="ms-3">
                                    <div class="mb-2">
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                    </div>
                                    <h5 class="text-uppercase">Ahmad Sudrajat</h5>
                                    <span>Warga Talaga</span>
                                </div>
                            </div>
                            <p class="fs-5">Pagar rumah saya dibuatkan oleh Bengkel Asyraf, hasilnya sangat rapi dan kokoh. Harga juga terjangkau. Sangat recommended!</p>
                        </div>
                        <div class="testimonial-item">
                            <div class="d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('img/testimonial-2.jpg') }}" alt="Siti Nurhaliza">
                                <div class="ms-3">
                                    <div class="mb-2">
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                    </div>
                                    <h5 class="text-uppercase">Siti Nurhaliza</h5>
                                    <span>Ibu Rumah Tangga</span>
                                </div>
                            </div>
                            <p class="fs-5">Kanopi rumah saya sangat bagus hasilnya. Proses pengerjaannya cepat dan tukangnya ramah. Terima kasih Bengkel Asyraf!</p>
                        </div>
                        <div class="testimonial-item">
                            <div class="d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('img/testimonial-3.jpg') }}" alt="Hendra Wijaya">
                                <div class="ms-3">
                                    <div class="mb-2">
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                    </div>
                                    <h5 class="text-uppercase">Hendra Wijaya</h5>
                                    <span>Kontraktor</span>
                                </div>
                            </div>
                            <p class="fs-5">Sudah beberapa kali order teralis dan railing di Bengkel Asyraf. Kualitasnya selalu konsisten dan tepat waktu. Partner kerja terbaik!</p>
                        </div>
                        <div class="testimonial-item">
                            <div class="d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('img/testimonial-4.jpg') }}" alt="Dewi Ratnasari">
                                <div class="ms-3">
                                    <div class="mb-2">
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                        <i class="far fa-star text-primary"></i>
                                    </div>
                                    <h5 class="text-uppercase">Dewi Ratnasari</h5>
                                    <span>Pemilik Toko</span>
                                </div>
                            </div>
                            <p class="fs-5">Pintu rolling door toko saya dibuatkan di sini. Hasilnya mantap, kuat, dan finishing-nya sangat bagus. Puas banget!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
@endsection

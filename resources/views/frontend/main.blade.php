<!doctype html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StomaSoft - Stomatologiya İdarəetmə Sistemi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('frontend/img/favicon.ico')}}">
    <style>
        body{font-family:Inter,Arial,sans-serif;background:#f7fbff;color:#102033}
        .hero{min-height:100vh;background:linear-gradient(135deg,#eef8ff,#ffffff)}
        .brand{font-size:28px;font-weight:800;color:#0d6efd}
        .hero-title{font-size:56px;font-weight:900;line-height:1.05}
        .hero-text{font-size:20px;color:#5b6b7d}
        .btn-main{padding:14px 28px;border-radius:14px;font-weight:700}
        .card-soft{border:0;border-radius:24px;box-shadow:0 15px 45px rgba(13,110,253,.10)}
        .feature-icon{width:58px;height:58px;border-radius:18px;background:#e7f1ff;color:#0d6efd;display:flex;align-items:center;justify-content:center;font-size:26px}
        .section-title{font-weight:900}
        .price-card{border:2px solid #0d6efd}
        .footer{background:#102033;color:#c8d3df}
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand brand" href="#">StomaSoft</a>

        <div class="ms-auto d-flex align-items-center gap-3">
            <a href="tel:+994103227575" class="text-decoration-none fw-bold text-dark">
                📞 +994 10 322 75 75
            </a>

            <a href="https://demo.{{ config('app.domain') }}" class="btn btn-outline-primary rounded-pill px-4">
                Demo
            </a>

            <a href="https://app.{{ config('app.domain') }}" class="btn btn-primary rounded-pill px-4">
                Sistemə giriş
            </a>
        </div>
    </div>
</nav>

<section class="hero d-flex align-items-center pt-5">
    <div class="container pt-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3">
                    Stomatoloji klinikalar üçün CRM
                </span>

                <h1 class="hero-title mb-4">
                    Klinik idarəetməni bir paneldə topla
                </h1>

                <p class="hero-text mb-4">
                    StomaSoft pasient bazası, rezervasiya, kassa, borc tarixçəsi,
                    partnyor ödənişləri, maliyyə statistikası və qəbz çapını bir paneldə idarə etmək üçün hazırlanmış sistemdir.
                </p>

                <div class="d-flex flex-wrap gap-3">
                    <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary btn-main">
                        Demoya bax
                    </a>

                    <a href="https://app.{{ config('app.domain') }}" class="btn btn-outline-primary btn-main">
                        Müştəri girişi
                    </a>
                </div>

                <p class="mt-4 text-muted">
                    4 dil dəstəyi: Azərbaycan, Rus , Türk və İngilis dili.
                </p>
            </div>

            <div class="col-lg-6">
                <div class="card card-soft p-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Panel imkanları</h4>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">CRM</h3>
                                    <p class="mb-0 text-muted">Pasient bazası və xidmət arxivi</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">Kassa</h3>
                                    <p class="mb-0 text-muted">Medaxil, məxaric və qəbz çapı</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">Rezerv</h3>
                                    <p class="mb-0 text-muted">Qəbul saatları və rezervlər</p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4">
                                    <h3 class="fw-bold text-primary">Hesabat</h3>
                                    <p class="mb-0 text-muted">Dövr üzrə maliyyə statistikası</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="section-title">StomaSoft nə edir</h2>
            <p class="text-muted">Klinikanın gündəlik işlərini sadələşdirir</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-soft h-100 p-4">
                    <div class="feature-icon mb-3">🦷</div>
                    <h5 class="fw-bold">Pasient bazası</h5>
                    <p class="text-muted mb-0">
                        Pasient məlumatları, borc tarixçəsi və göstərilən xidmətlərin arxivi.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100 p-4">
                    <div class="feature-icon mb-3">📊</div>
                    <h5 class="fw-bold">Maliyyə statistikası</h5>
                    <p class="text-muted mb-0">
                        Dövr üzrə gəlir, məxaric, medaxil və ümumi maliyyə hesabatları.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100 p-4">
                    <div class="feature-icon mb-3">💳</div>
                    <h5 class="fw-bold">Kassa və ödənişlər</h5>
                    <p class="text-muted mb-0">
                        Medaxil, məxaric, pasient ödənişləri və qəbz çapı.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100 p-4">
                    <div class="feature-icon mb-3">📅</div>
                    <h5 class="fw-bold">Rezervasiya sistemi</h5>
                    <p class="text-muted mb-0">
                        Həkimlər üzrə qəbul saatları, boş vaxtlar və rezerv statusları.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100 p-4">
                    <div class="feature-icon mb-3">🤝</div>
                    <h5 class="fw-bold">Partnyor ödənişləri</h5>
                    <p class="text-muted mb-0">
                        Laboratoriya və partnyorlara olan borc və ödənişlərin idarəsi.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-soft h-100 p-4">
                    <div class="feature-icon mb-3">📁</div>
                    <h5 class="fw-bold">Rentgen faylları</h5>
                    <p class="text-muted mb-0">
                        Pasientə aid rentgen və digər faylların yüklənməsi və arxivlənməsi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="section-title mb-3">Klinikanız üçün hazır SaaS sistem</h2>
                <p class="text-muted fs-5">
                    Server, panel, yeniləmə və texniki dəstək bizdədir.
                    Siz sadəcə sistemə daxil olub klinikanızı idarə edirsiniz.
                </p>

                <ul class="list-unstyled fs-5">
                    <li class="mb-2">✅ Sürətli istifadə</li>
                    <li class="mb-2">✅ Aylıq abunə</li>
                    <li class="mb-2">✅ Demo ilə əvvəlcədən test</li>
                    <li class="mb-2">✅ Həkimlərə görə məlumat bölgüsü</li>
                    <li class="mb-2">✅ Azərbaycan, Rus və İngilis dili</li>
                    <li class="mb-2">✅ Qəbz çapı və fayl idarəetməsi</li>
                </ul>
            </div>

            <div class="col-lg-6">
                <div class="card card-soft price-card p-4">
                    <div class="card-body text-center">
                        <h3 class="fw-bold">Başlanğıc paket</h3>
                        <p class="text-muted">Kiçik və orta stomatoloji klinikalar üçün</p>

                        <div class="display-4 fw-bold text-primary my-4">
                            99 AZN
                            <span class="fs-5 text-muted">/ ay</span>
                        </div>

                        <p class="text-muted">
                            Pasient bazası, rezervasiya, kassa, maliyyə statistikası,
                            qəbz çapı və fayl idarəetməsi daxildir.
                        </p>

                        <a href="https://demo.{{ config('app.domain') }}" class="btn btn-primary btn-main w-100">
                            Demoya keç
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-primary text-white">
    <div class="container py-4 text-center">
        <h2 class="fw-bold mb-3">StomaSoft-u indi yoxla</h2>
        <p class="mb-4 fs-5 opacity-75">
            Demo panelə daxil ol və sistemin klinikan üçün uyğun olub-olmadığını yoxla.
        </p>

        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="https://demo.{{ config('app.domain') }}" class="btn btn-light btn-main text-primary">
                Demo giriş
            </a>

            <a href="https://app.{{ config('app.domain') }}" class="btn btn-outline-light btn-main">
                Müştəri paneli
            </a>
        </div>

        <p class="mt-4 mb-0 opacity-75">
            Əlaqə: +994 10 322 75 75
        </p>
    </div>
</section>

<footer class="footer py-4">
    <div class="container d-flex flex-wrap justify-content-between gap-3 align-items-center">
        <div>
            <b>StomaSoft</b> — Stomatologiya idarəetmə sistemi
        </div>

        <div>
            📞 +994 10 322 75 75
        </div>

        <div>
            © {{ date('Y') }}
        </div>
    </div>
</footer>

</body>
</html>

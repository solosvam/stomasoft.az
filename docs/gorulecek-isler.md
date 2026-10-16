# StomaSoft — görüləcək işlər

## Qüvvədə olan qərarlar

- Ictimai sayt (`/`, `/doctors`, `/patients`) açıq fonda, logo rənglərində (tünd göy `#072161`, mavi `#0A62D8`, səma mavisi `#11ACEA`) hazırlanır. Tünd fon yoxdur.
- Üç səhifə ortaq `public/frontend/css/site.css` və `resources/views/frontend/partials/` istifadə edir.
- `/` — sistemi izah edən landing; `/doctors` — bütün modullar, tarif və sual-cavab; `/patients` — pasientin onlayn rezervasiyası.
- Landing-də əlaqə formu yoxdur: yalnız zəng və demo (`demo.stomasoft.az`).
- 4 dil (az, en, ru, tr). Yeni mətnlər `lang/*.json`-da `site.*` açarlarıdır.
- Dil URL ilə müəyyən olunur: az kökdə (`/`, `/doctors`), digərləri `/en`, `/ru`, `/tr` prefiksi ilə. Köhnə `?lang=xx` 301 ilə yeni ünvana yönləndirilir. Marşrutlar `routes/web.php`-də, köməkçi sinif `App\Support\Landing` (`$L` kimi view-lara paylaşılıb).
- SEO: hər səhifə/dil üçün ayrıca title+description (`site.seo_*`), canonical, hreflang, og:*, JSON-LD (ana səhifədə), `/sitemap.xml` (`SeoController`). `public/robots.txt` statikdir (nginx `/robots.txt`-i PHP-yə vermir).
- `app.` və `demo.` subdomenləri və panel marşrutları `X-Robots-Tag: noindex` alır (`NoIndexPrivateAreas`); `robots.txt` onları bağlamır ki, Google noindex-i görsün.
- Qiymətlər: AZ — 99 AZN/ay və 3 aylıq paketdə 50 AZN/ay; digər dillərdə 59 USD və 29 USD.

## Açıq suallar

- Telefon nömrəsi: köhnə səhifələrdə `+994 10 322 75 75` və `+994 50 432 1 103` qarışıq idi; yeni səhifələrdə `+994 50 432 1 103` istifadə olunur. Düzgünlüyünü təsdiq et.
- Landing-dəki ekran maketləri HTML/CSS ilə çəkilib (uydurma məlumatlarla). İstənilərsə real ekran görüntüləri ilə əvəzlənə bilər.

## Görüləcək

- [ ] TƏHLÜKƏSİZLİK: pasient faylları (rentgen və s.) `public/backend/uploads/patients/{id}/` altında saxlanılır — linki bilən hər kəs açır (`PatientController` `public_path` istifadə edir) və fayllar git-ə də düşüb. Fayllar `storage/app` (private) + giriş yoxlayan route ilə verilməli, repodan çıxarılmalıdır. `robots.txt` yalnız Google-dan gizlədir, qorumur.
- [ ] Deploy-dan sonra serverdə `php artisan route:clear` (və cache istifadə olunursa `view:clear`).
- [ ] Google Search Console: `https://stomasoft.az/sitemap.xml` təqdim et; `app.`/`demo.` üçün lazım gəlsə "Removals" ilə köhnə indeksi sil.
- [ ] Paylaşım önizləməsini yoxla (WhatsApp/Telegram/Facebook Debugger) — `og-image.jpg` AZ mətnlidir, istənilərsə RU/EN/TR variantı.
- [ ] Canlı saytda `app.`/`demo.` yönləndirmələri `http://`-ə gedir (`Location: http://app.stomasoft.az/login`) — `APP_URL`/Cloudflare-i yoxla.

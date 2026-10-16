# StomaSoft — görüləcək işlər

## Qüvvədə olan qərarlar

- Ictimai sayt (`/`, `/doctors`, `/patients`) açıq fonda, logo rənglərində (tünd göy `#072161`, mavi `#0A62D8`, səma mavisi `#11ACEA`) hazırlanır. Tünd fon yoxdur.
- Üç səhifə ortaq `public/frontend/css/site.css` və `resources/views/frontend/partials/` istifadə edir.
- `/` — sistemi izah edən landing; `/doctors` — bütün modullar, tarif və sual-cavab; `/patients` — pasientin onlayn rezervasiyası.
- Landing-də əlaqə formu yoxdur: yalnız zəng və demo (`demo.stomasoft.az`).
- 4 dil (az, en, ru, tr). Yeni mətnlər `lang/*.json`-da `site.*` açarlarıdır.
- Qiymətlər: AZ — 99 AZN/ay və 3 aylıq paketdə 50 AZN/ay; digər dillərdə 59 USD və 29 USD.

## Açıq suallar

- Telefon nömrəsi: köhnə səhifələrdə `+994 10 322 75 75` və `+994 50 432 1 103` qarışıq idi; yeni səhifələrdə `+994 50 432 1 103` istifadə olunur. Düzgünlüyünü təsdiq et.
- `frontend/img/og-image.jpg` faylı yoxdur (paylaşım önizləməsi boş çıxır).
- Landing-dəki ekran maketləri HTML/CSS ilə çəkilib (uydurma məlumatlarla). İstənilərsə real ekran görüntüləri ilə əvəzlənə bilər.

## Görüləcək

- [ ] Landing dəyişiklikləri canlı serverdə yoxlanılsın (xüsusilə `?lang=` ilə dillər).

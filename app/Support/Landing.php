<?php

namespace App\Support;

/**
 * URL scheme of the public site. Azerbaijani lives at the root, other
 * languages under a prefix (/ru, /en/doctors, ...), so every language has
 * its own crawlable address.
 */
class Landing
{
    public const LOCALES = ['az', 'en', 'ru', 'tr'];

    public const PAGES = [
        'home'     => '',
        'doctors'  => 'doctors',
        'patients' => 'patients',
    ];

    public const OG_LOCALES = [
        'az' => 'az_AZ',
        'en' => 'en_US',
        'ru' => 'ru_RU',
        'tr' => 'tr_TR',
    ];

    /** Page key (home|doctors|patients) for a landing route name, null for anything else. */
    public static function pageOf(?string $routeName): ?string
    {
        if ($routeName && preg_match('/^page\.(?:(?:en|ru|tr)\.)?(homee|home|doctors|patients)$/', $routeName, $m)) {
            return $m[1] === 'homee' ? 'home' : $m[1];
        }

        return null;
    }

    public function path(string $page, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $prefix = $locale === 'az' ? '' : $locale;

        return '/' . trim($prefix . '/' . self::PAGES[$page], '/');
    }

    /** Link on the current host, in the current (or given) language. */
    public function url(string $page, ?string $locale = null): string
    {
        return url($this->path($page, $locale));
    }

    public function currentPage(): string
    {
        return self::pageOf(request()->route()?->getName()) ?? 'home';
    }

    /** Absolute public address used in canonical, hreflang, sitemap and og:url. */
    public function canonical(?string $page = null, ?string $locale = null): string
    {
        return 'https://' . config('app.domain') . $this->path($page ?? $this->currentPage(), $locale);
    }

    /** hreflang => canonical URL for every language of a page, plus x-default. */
    public function alternates(?string $page = null): array
    {
        $page ??= $this->currentPage();
        $out = [];

        foreach (self::LOCALES as $locale) {
            $out[$locale] = $this->canonical($page, $locale);
        }

        $out['x-default'] = $this->canonical($page, 'az');

        return $out;
    }

    /** Same page in another language (for the language switcher). */
    public function switchUrl(string $locale): string
    {
        return $this->url($this->currentPage(), $locale);
    }

    public function ogLocale(?string $locale = null): string
    {
        return self::OG_LOCALES[$locale ?? app()->getLocale()] ?? 'az_AZ';
    }
}

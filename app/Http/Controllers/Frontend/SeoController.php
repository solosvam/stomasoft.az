<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\Landing;

class SeoController extends Controller
{
    public function sitemap(Landing $landing)
    {
        $files = [
            'home'     => 'main',
            'doctors'  => 'doctors',
            'patients' => 'patients',
        ];

        $urls = [];
        foreach ($files as $page => $view) {
            $lastmod = date('Y-m-d', filemtime(resource_path("views/frontend/$view.blade.php")));

            foreach (Landing::LOCALES as $locale) {
                $urls[] = [
                    'loc'        => $landing->canonical($page, $locale),
                    'lastmod'    => $lastmod,
                    'priority'   => $page === 'home' ? '1.0' : '0.8',
                    'alternates' => $landing->alternates($page),
                ];
            }
        }

        // Built as a plain string: Blade chokes on "<?xml" when short_open_tag is enabled
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach ($urls as $u) {
            $xml .= "    <url>\n"
                . '        <loc>' . e($u['loc']) . "</loc>\n"
                . '        <lastmod>' . $u['lastmod'] . "</lastmod>\n"
                . '        <priority>' . $u['priority'] . "</priority>\n";

            foreach ($u['alternates'] as $hreflang => $href) {
                $xml .= '        <xhtml:link rel="alternate" hreflang="' . $hreflang . '" href="' . e($href) . '"/>' . "\n";
            }

            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>' . "\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}

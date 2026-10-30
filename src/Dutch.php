<?php

namespace Spdotdev\SplotnikovDev;

/**
 * The Dutch version of the site at /nl/...: the same views, rendered in English by the controllers, then
 * switched to Dutch on the server with the strings every page already carries for the JS switch.
 * Only pages that exist in full in both languages are paired (home, CV, contact, projects, toolkit);
 * blog posts stay English and have no /nl/ twin.
 */
final class Dutch
{
    public const HOST = 'https://splotnikov.dev';

    /** @var list<string> */
    private const FIXED = ['/', '/cv', '/contact', '/projects', '/toolkit', '/privacy'];

    public static function paired(string $path): bool
    {
        $path = '/'.trim($path, '/');
        if (in_array($path, self::FIXED, true)) {
            return true;
        }

        return false;
    }

    /** The English path as the controllers know it, from a path under /nl. */
    public static function enPath(string $nlPath): string
    {
        $p = '/'.trim($nlPath, '/');
        $p = (string) preg_replace('#^/nl#', '', $p);

        return $p === '' ? '/' : $p;
    }

    public static function nlUrl(string $enPath): string
    {
        $enPath = '/'.trim($enPath, '/');

        return self::HOST.'/nl'.($enPath === '/' ? '' : $enPath);
    }

    public static function enUrl(string $enPath): string
    {
        $enPath = '/'.trim($enPath, '/');

        return self::HOST.($enPath === '/' ? '/' : $enPath);
    }

    /**
     * Dutch title and description for a paired page.
     *
     * @return array{0:string,1:string}
     */
    public static function meta(string $enPath): array
    {
        $p = '/'.trim($enPath, '/');
        $fixed = [
            '/' => ['Senior developer en architect, Eindhoven', 'Stanislav Plotnikov: automatisering met AI-agents, modernisering van legacy en security. PHP sinds 2008. Beschikbaar als freelancer, Eindhoven of remote.'],
            '/cv' => ['CV', 'Curriculum vitae van Stanislav Plotnikov: senior developer en solution architect in Eindhoven, PHP sinds 2008, Laravel, Python, AI-agents en Linux. Printbaar en als PDF.'],
            '/contact' => ['Contact', 'Neem contact op met Stanislav Plotnikov in Eindhoven: het formulier, stplotnikov@gmail.com, LinkedIn en GitHub. Ik reageer binnen één werkdag.'],
            '/projects' => ['Projecten', 'Open source projecten van Stanislav Plotnikov: een WhatsApp MCP-server, een Claude Code-skill voor handboeken, een Telegram-relay, een voorraadproduct en deze site.'],
            '/privacy' => ['Privacyverklaring', 'Welke gegevens splotnikov.dev gebruikt: geen cookies bij het lezen, een eigen teller zonder IP-adres, en het contactformulier.'],
            '/toolkit' => ['Tools', 'De tools en werkwijzen die Stanislav Plotnikov dagelijks gebruikt: PHP en Laravel, AI-agents met vangrails, security, infrastructure as code en pipelines die op rood blokkeren.'],
        ];

        return $fixed[$p] ?? ['', ''];
    }

    public static function fit(string $title, string $suffix = ' | Stanislav Plotnikov'): string
    {
        $t = mb_strlen($title.$suffix) <= 66 ? $title.$suffix : $title;
        if (mb_strlen($t) > 70) {
            $t = rtrim((string) preg_replace('/\s+\S*$/u', '', mb_substr($t, 0, 68)), ' ,.:;-').'…';
        }

        return $t;
    }

    public static function fitDescription(string $d): string
    {
        $d = trim((string) preg_replace('/\s+/', ' ', $d));
        if (mb_strlen($d) <= 160) {
            return $d;
        }
        $cut = mb_substr($d, 0, 160);
        if (preg_match('/^(.{80,}(?<!excl)(?<!e\.g)[.!?])\s/us', $cut, $m)) {
            return $m[1];
        }

        return rtrim((string) preg_replace('/\s+\S*$/u', '', mb_substr($d, 0, 157)), ' ,.:;-').'…';
    }

    /** The Dutch strings the page pushes for the JS switch, read from the rendered HTML. */
    public static function strings(string $html): array
    {
        $map = [];
        if (! preg_match_all('#<script>\s*(?:window\.SCUTTLE_NL|Object\.assign\(window\.SCUTTLE_NL\.t)(.*?)</script>#s', $html, $blocks)) {
            return $map;
        }
        foreach ($blocks[1] as $block) {
            if (preg_match_all('/(?:"([A-Za-z0-9_-]+)"|([A-Za-z0-9_]+))\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/s', $block, $pairs, PREG_SET_ORDER)) {
                foreach ($pairs as $pair) {
                    $key = $pair[1] !== '' ? $pair[1] : $pair[2];
                    $val = json_decode('"'.$pair[3].'"');
                    if (is_string($val)) {
                        $map[$key] = $val;
                    }
                }
            }
        }

        return $map;
    }

    /** Replace the inside of every element with data-i="key" by its Dutch string, like the JS switch does. */
    public static function translate(string $html, array $map): string
    {
        $void = ['input', 'img', 'br', 'hr', 'meta', 'link'];
        $out = '';
        $pos = 0;
        $len = strlen($html);
        while ($pos < $len && preg_match('/<([a-z][a-z0-9]*)\b[^>]*?\sdata-i="([^"]+)"[^>]*>/i', $html, $m, PREG_OFFSET_CAPTURE, $pos)) {
            [$open, $start] = $m[0];
            $tag = strtolower($m[1][0]);
            $key = $m[2][0];
            $end = $start + strlen($open);
            $out .= substr($html, $pos, $end - $pos);
            $pos = $end;
            if (in_array($tag, $void, true) || str_ends_with($open, '/>') || ! isset($map[$key])) {
                continue;
            }
            // find the matching close tag
            $depth = 1;
            $scan = $end;
            $closeAt = null;
            while ($depth > 0 && preg_match('#<(/?)'.preg_quote($tag, '#').'\b[^>]*>#i', $html, $t, PREG_OFFSET_CAPTURE, $scan)) {
                $isClose = $t[1][0] === '/';
                $depth += $isClose ? -1 : (str_ends_with($t[0][0], '/>') ? 0 : 1);
                $scan = $t[0][1] + strlen($t[0][0]);
                if ($depth === 0) {
                    $closeAt = $t[0][1];
                }
            }
            if ($closeAt === null) {
                continue;
            }
            $out .= $map[$key];
            $pos = $closeAt;
        }

        return $out.substr($html, $pos);
    }

    public static function transform(string $html, string $enPath): string
    {
        $map = self::strings($html);
        $html = self::translate($html, $map);

        // links to paired pages stay in Dutch, whether written as a full address or as a plain path
        $html = (string) preg_replace_callback('#<a\b[^>]*>#', function (array $a): string {
            if (str_contains($a[0], 'hreflang=')) {
                return $a[0];
            }

            return (string) preg_replace_callback('#href="(?:https://splotnikov\.dev)?(/[^"\#?]*)([\#?][^"]*)?"#', function (array $m): string {
                if (str_starts_with($m[1], '/nl') || ! self::paired($m[1])) {
                    return $m[0];
                }

                return 'href="'.self::nlUrl($m[1]).($m[2] ?? '').'"';
            }, $a[0]);
        }, $html);

        [$title, $desc] = self::meta($enPath);
        if ($title !== '') {
            $t = e(self::fit($title));
            $d = e(self::fitDescription($desc));
            $html = (string) preg_replace('#<title>.*?</title>#s', '<title>'.$t.'</title>', $html, 1);
            $html = (string) preg_replace('#(<meta (?:name|property)="(?:description|og:description|twitter:description)" content=")[^"]*(")#', '${1}'.$d.'${2}', $html);
            $html = (string) preg_replace('#(<meta (?:property="og:title"|name="twitter:title") content=")[^"]*(")#', '${1}'.$t.'${2}', $html);
        }
        $nl = self::nlUrl($enPath);
        $html = (string) preg_replace('#<html lang="en"#', '<html lang="nl"', $html, 1);
        $html = (string) preg_replace('#(<link rel="canonical" href=")[^"]*(")#', '${1}'.$nl.'${2}', $html, 1);
        $html = (string) preg_replace('#(<meta property="og:url" content=")[^"]*(")#', '${1}'.$nl.'${2}', $html, 1);
        $html = str_replace(['content="en_GB"', 'content="nl_NL" />'], ['content="NL_PRIMARY"', 'content="nl_NL" />'], $html);
        $html = str_replace('<meta property="og:locale:alternate" content="nl_NL" />', '<meta property="og:locale:alternate" content="en_GB" />', $html);
        $html = str_replace('content="NL_PRIMARY"', 'content="nl_NL"', $html);

        return $html;
    }
}

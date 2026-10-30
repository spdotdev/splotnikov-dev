<?php

namespace Spdotdev\SplotnikovDev;

use Carbon\Carbon;

/**
 * The archive next to the post list: posts grouped by year, month and day, a filter on one of those,
 * and a page of the result. Pure functions over the Post arrays, shared by the blog and (in scuttle-dev) the news.
 *
 * @phpstan-type Row array{slug:string, title:string, date:string}
 */
final class Archive
{
    public const PER_PAGE = 6;

    /**
     * Year => month => day => number of posts, newest first on every level.
     *
     * @param  array<string, Row>  $posts
     * @return array<string, array<string, array<string, int>>>
     */
    public static function tree(array $posts): array
    {
        $tree = [];
        foreach ($posts as $post) {
            [$y, $m, $d] = explode('-', $post['date']);
            $tree[$y][$m][$d] = ($tree[$y][$m][$d] ?? 0) + 1;
        }
        krsort($tree);
        foreach ($tree as &$months) {
            krsort($months);
            foreach ($months as &$days) {
                krsort($days);
            }
        }

        return $tree;
    }

    /**
     * The posts of one period: "2026", "2026-10" or "2026-10-29"; empty filter keeps all. Newest first.
     *
     * @param  array<string, Row>  $posts
     * @return array<string, Row>
     */
    public static function filter(array $posts, ?string $period): array
    {
        $period = trim((string) $period);
        if ($period !== '' && ! preg_match('/^\d{4}(-\d{2}){0,2}$/', $period)) {
            $period = '';
        }
        $kept = array_filter($posts, fn (array $p): bool => $period === '' || str_starts_with($p['date'], $period));
        uasort($kept, fn (array $a, array $b): int => strcmp($b['date'], $a['date']));

        return $kept;
    }

    /**
     * One page of the list and the page count.
     *
     * @param  array<string, Row>  $posts
     * @return array{0: array<string, Row>, 1: int, 2: int} the page's posts, the page number, the number of pages
     */
    public static function page(array $posts, mixed $page, int $perPage = self::PER_PAGE): array
    {
        $pages = max(1, (int) ceil(count($posts) / $perPage));
        $page = min($pages, max(1, (int) $page));

        return [array_slice($posts, ($page - 1) * $perPage, $perPage, true), $page, $pages];
    }

    /** A readable period label: "October 2026", "29 October 2026" or "2026", in the given locale. */
    public static function label(string $period, string $locale = 'en'): string
    {
        $parts = explode('-', $period);
        $date = Carbon::create((int) $parts[0], (int) ($parts[1] ?? 1), (int) ($parts[2] ?? 1))->locale($locale);

        return match (count($parts)) {
            1 => $parts[0],
            2 => $date->translatedFormat('F Y'),
            default => $date->translatedFormat('j F Y'),
        };
    }
}

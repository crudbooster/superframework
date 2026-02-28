<?php

if (!function_exists('minify_asset')) {
    function minify_asset(string $type, string $content): string
    {
        if ($type === 'css') {
            $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
            $content = str_replace(["\r\n", "\r", "\n", "\t"], '', $content);
            $content = preg_replace('/\s+/', ' ', $content);
            $content = str_replace([' {', '{ ', ' }', '} ', ' :', ': ', ' ;', '; '], ['{', '{', '}', '}', ':', ':', ';', ';'], $content);
            $content = trim($content);
        } elseif ($type === 'js') {
            $content = preg_replace('/(\r\n|\n|\r|\t)/', '', $content);
            $content = preg_replace('/\s+/', ' ', $content);
            $content = trim($content);
        }
        return $content;
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return "/assets/" . ltrim($path, "/");
    }
}

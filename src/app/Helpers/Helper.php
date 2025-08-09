<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Helper
{

    public static function extractImageUrls($html) {
        preg_match_all('/<img[^>]+src="([^">]+)"/', $html, $matches);
        return $matches[1] ?? [];
    }
}

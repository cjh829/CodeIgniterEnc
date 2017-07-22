<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return (strpos($haystack, $needle) !== false);
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        return (substr($haystack, 0, strlen($needle)) === $needle);
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle)
    {
        return substr($haystack, -strlen($needle)) === $needle;
    }
}

if (!function_exists('str_cut_tail')) {
    function str_cut_tail($haystack, $needle)
    {
        if (str_ends_with($haystack, $needle)) {
            return substr($haystack, 0, strlen($haystack) - strlen($needle));
        }
        return $haystack;
    }
}
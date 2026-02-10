#!/usr/bin/env php
<?php

/**
 * Scans Blade views for occurrences of __('...') and reports translation keys
 * that are missing in lang/en.json and lang/es.json.
 *
 * Usage: php scripts/find-missing-translations.php
 */
$base = __DIR__.'/..';
$viewsDir = $base.'/resources/views';
$langDir = $base.'/lang';

if (! is_dir($viewsDir)) {
    fwrite(STDERR, "resources/views directory not found.\n");
    exit(2);
}

// Recursively collect blade files
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$bladeFiles = [];
foreach ($rii as $file) {
    if ($file->isDir()) {
        continue;
    }
    if (str_ends_with($file->getFilename(), '.blade.php')) {
        $bladeFiles[] = $file->getPathname();
    }
}

if (empty($bladeFiles)) {
    echo "No Blade files found under resources/views.\n";
    exit(0);
}

$keys = [];
// Match __('...') or __("...") and handle escaped quotes inside the string
$pattern = '/__\\(\\s*([\\\'\"])((?:\\\\.|(?!\\1).)*)\\1/s';

foreach ($bladeFiles as $file) {
    $contents = file_get_contents($file);
    if ($contents === false) {
        continue;
    }
    if (preg_match_all($pattern, $contents, $matches)) {
        foreach ($matches[2] as $m) {
            // Unescape any escaped characters (e.g., Don\'t -> Don't)
            $m = stripcslashes(trim($m));
            // Normalize whitespace and remove newlines introduced in templates
            $m = preg_replace('/\s+/u', ' ', $m);
            if ($m === '') {
                continue;
            }
            $keys[$m] = true;
        }
    }
}

$uniqueKeys = array_keys($keys);
sort($uniqueKeys);

echo 'Found '.count($uniqueKeys)." unique translation keys in Blade views.\n";

// Load translations
$locales = ['en', 'es'];
$translations = [];
foreach ($locales as $locale) {
    $path = $langDir.'/'.$locale.'.json';
    if (! file_exists($path)) {
        fwrite(STDERR, "Warning: language file not found: {$path}\n");
        $translations[$locale] = [];

        continue;
    }
    $json = json_decode(file_get_contents($path), true);
    if (! is_array($json)) {
        fwrite(STDERR, "Warning: could not decode JSON in {$path}\n");
        $translations[$locale] = [];

        continue;
    }
    $translations[$locale] = array_keys(flattenKeys($json));
}

// Compare
$missing = [];
foreach ($locales as $locale) {
    $missing[$locale] = [];
    $have = array_flip($translations[$locale]);
    foreach ($uniqueKeys as $k) {
        if (! isset($have[$k])) {
            $missing[$locale][] = $k;
        }
    }
}

// Report
foreach ($locales as $locale) {
    echo "\nLocale: {$locale}\n";
    $count = count($missing[$locale]);
    if ($count === 0) {
        echo "  All keys present (0 missing).\n";

        continue;
    }
    echo "  Missing keys: {$count}\n";
    foreach ($missing[$locale] as $k) {
        echo "    - {$k}\n";
    }
}

// Exit with non-zero status if any missing
$totalMissing = array_sum(array_map('count', $missing));
if ($totalMissing > 0) {
    echo "\nTotal missing translations: {$totalMissing}\n";
    exit(1);
}

echo "\nNo missing translations found.\n";
exit(0);

/**
 * Flatten nested arrays into dot.notation => value map.
 * For JSON translation files that use nested objects, this will flatten.
 */
function flattenKeys(array $arr, string $prefix = ''): array
{
    $out = [];
    foreach ($arr as $k => $v) {
        $key = $prefix === '' ? $k : ($prefix.'.'.$k);
        if (is_array($v)) {
            $out = $out + flattenKeys($v, $key);
        } else {
            $out[$key] = $v;
        }
    }

    return $out;
}

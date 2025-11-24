<?php
// script para extrair cor média do logo e gerar CSS com variáveis
$logo = __DIR__ . '/../public/logo.jpg';
$outCss = __DIR__ . '/../public/css/logo-colors.css';

if (!file_exists($logo)) {
    fwrite(STDERR, "Logo not found: $logo\n");
    exit(1);
}

if (!extension_loaded('gd')) {
    fwrite(STDERR, "GD extension not available.\n");
    exit(1);
}

$img = @imagecreatefromstring(file_get_contents($logo));
if (!$img) {
    fwrite(STDERR, "Failed to create image from logo.\n");
    exit(1);
}

$w = imagesx($img);
$h = imagesy($img);

$step = max(1, intval(min($w, $h) / 100)); // sample up to ~100x100
$tot = 0;
$r = $g = $b = 0;
for ($x = 0; $x < $w; $x += $step) {
    for ($y = 0; $y < $h; $y += $step) {
        $rgb = imagecolorat($img, $x, $y);
        $r += ($rgb >> 16) & 0xFF;
        $g += ($rgb >> 8) & 0xFF;
        $b += $rgb & 0xFF;
        $tot++;
    }
}

if ($tot === 0) {
    fwrite(STDERR, "No pixels sampled.\n");
    exit(1);
}

$r = round($r / $tot);
$g = round($g / $tot);
$b = round($b / $tot);

function rgb2hex($r, $g, $b) {
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

function adjust_brightness($r, $g, $b, $factor) {
    $r = max(0, min(255, round($r * $factor)));
    $g = max(0, min(255, round($g * $factor)));
    $b = max(0, min(255, round($b * $factor)));
    return [$r, $g, $b];
}

$primary = rgb2hex($r, $g, $b);
list($ar, $ag, $ab) = adjust_brightness($r, $g, $b, 0.85);
$accent = rgb2hex($ar, $ag, $ab);

// determine on-primary color (black or white)
$luminance = (0.2126 * ($r/255) + 0.7152 * ($g/255) + 0.0722 * ($b/255));
$onPrimary = ($luminance > 0.6) ? '#111827' : '#ffffff';

$css = ":root {\n" .
       "  --logo-primary: $primary;\n" .
       "  --logo-accent: $accent;\n" .
       "  --logo-on-primary: $onPrimary;\n" .
       "}\n";

@mkdir(dirname($outCss), 0755, true);
file_put_contents($outCss, $css);

echo "Generated CSS: $outCss\n";
exit(0);

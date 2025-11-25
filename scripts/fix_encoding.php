<?php
// Script to fix encoding of category and brand 'nome' fields by converting from CP1252 to UTF-8
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;
use App\Models\Brand;

function convertIfNeeded($s) {
    if ($s === null) return $s;
    $s = trim($s);
    if ($s === '') return $s;
    if (mb_check_encoding($s, 'UTF-8')) return $s;
    return mb_convert_encoding($s, 'UTF-8', 'CP1252');
}

echo "Fixing categories...\n";
$fixed = 0;
foreach (Category::all() as $c) {
    $orig = $c->nome;
    $conv = convertIfNeeded($orig);
    if ($conv !== $orig) {
        $c->nome = $conv;
        $c->save();
        $fixed++;
        echo "Fixed category {$c->id}: {$orig} -> {$conv}\n";
    }
}
echo "Categories fixed: {$fixed}\n";

echo "Fixing brands...\n";
$fixed = 0;
foreach (Brand::all() as $b) {
    $orig = $b->nome;
    $conv = convertIfNeeded($orig);
    if ($conv !== $orig) {
        $b->nome = $conv;
        $b->save();
        $fixed++;
        echo "Fixed brand {$b->id}: {$orig} -> {$conv}\n";
    }
}
echo "Brands fixed: {$fixed}\n";

echo "Done.\n";

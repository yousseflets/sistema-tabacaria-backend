<?php
// Simple CSV importer that doesn't require maatwebsite/excel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function normalizeHeader($h) {
    $h = trim(mb_strtolower($h));
    // remove BOM
    if (substr($h, 0, 3) === "\xEF\xBB\xBF") {
        $h = substr($h, 3);
    }
    return $h;
}

function ensureUtf8($s) {
    if ($s === null) return null;
    $s = trim($s);
    if ($s === '') return $s;
    if (mb_check_encoding($s, 'UTF-8')) return $s;
    // common Windows-1252 -> UTF-8 fallback
    return mb_convert_encoding($s, 'UTF-8', 'CP1252');
}

$file = storage_path('app/imports/sample-products.csv');
if (!file_exists($file)) {
    echo "File not found: $file\n";
    exit(1);
}

echo "Using file: $file\n";

$before = App\Models\Product::count();
echo "Products before: $before\n";

$handle = fopen($file, 'r');
if (!$handle) {
    echo "Unable to open file.\n";
    exit(1);
}

$header = null;
$rowIndex = 0;
$errors = [];
$created = [];
while (($line = fgets($handle)) !== false) {
    $rowIndex++;
    // try to detect delimiter: prefer semicolon
    if ($header === null) {
        // support both ; and ,
        $probe = $line;
        $delimiter = strpos($probe, ';') !== false ? ';' : ',';
        $parts = str_getcsv($line, $delimiter);
        $header = array_map('normalizeHeader', $parts);
        continue;
    }

    $delimiter = strpos($line, ';') !== false ? ';' : ',';
    $parts = str_getcsv($line, $delimiter);
    if (count($parts) === 0) continue;

    $data = [];
    foreach ($header as $i => $h) {
        $data[$h] = $parts[$i] ?? null;
    }

    $name = ensureUtf8($data['name'] ?? $data['nome'] ?? null);
    $price = ensureUtf8($data['price'] ?? $data['preco'] ?? null);
    $quantity = ensureUtf8($data['quantity'] ?? $data['quantidade'] ?? 0);
    $categoryName = ensureUtf8($data['category'] ?? $data['categoria'] ?? null);
    $brandName = ensureUtf8($data['brand'] ?? $data['marca'] ?? null);

    if (!$name || !$price) {
        $errors[] = [ 'row' => $rowIndex, 'error' => 'name or price missing', 'data' => $data ];
        continue;
    }

    // normalize price
    $price = str_replace([' ', 'R$', '\$'], ['', '', ''], $price);
    $price = str_replace(',', '.', $price);
    $price = floatval($price);

    // find or create category/brand
    $categoryId = null;
    if ($categoryName) {
        $cat = App\Models\Category::firstOrCreate(['nome' => trim($categoryName)]);
        $categoryId = $cat->id;
    }
    $brandId = null;
    if ($brandName) {
        $br = App\Models\Brand::firstOrCreate(['nome' => trim($brandName)]);
        $brandId = $br->id;
    }

    try {
        $prod = App\Models\Product::updateOrCreate(
            ['name' => $name],
            [
                'name' => $name,
                'price' => $price,
                'quantity' => intval($quantity),
                'active' => 1,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
            ]
        );
        $created[] = $prod;
    } catch (Throwable $e) {
        $errors[] = [ 'row' => $rowIndex, 'error' => $e->getMessage(), 'data' => $data ];
    }
}
fclose($handle);

$after = App\Models\Product::count();
echo "Products after: $after\n";
if (!empty($created)) {
    echo "Created/updated products (up to 10):\n";
    $slice = array_slice($created, 0, 10);
    foreach ($slice as $p) {
        echo " - {$p->id} | {$p->name} | {$p->price} | qty:{$p->quantity}\n";
    }
}
if (!empty($errors)) {
    echo "Errors:\n";
    foreach ($errors as $err) {
        echo "Row {$err['row']}: {$err['error']}\n";
    }
}

echo "Done.\n";

exit(0);

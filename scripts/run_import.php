<?php
// script to run products import from CLI (outside tinker) and exit
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// run import
try {
    echo "Starting import...\n";
    // show counts before
    $before = App\Models\Product::count();
    echo "Products before: {$before}\n";
    // ensure classes are available
    $import = new App\Imports\ProductsImport();
    \Maatwebsite\Excel\Facades\Excel::import($import, storage_path('app/imports/sample-products.csv'));
    echo "Import finished.\n";
    $after = App\Models\Product::count();
    echo "Products after: {$after}\n";
    if ($after > $before) {
        $new = App\Models\Product::orderBy('created_at','desc')->take(5)->get();
        echo "New products (up to 5):\n";
        foreach ($new as $p) {
            echo " - {$p->id} | {$p->name} | {$p->price} | qty:{$p->quantity}\n";
        }
    }
    if (!empty($import->errors)) {
        echo "Import completed with errors:\n";
        foreach ($import->errors as $err) {
            echo "Row {$err['row']}: " . implode('; ', $err['errors']) . "\n";
        }
    }
} catch (Throwable $e) {
    echo "Import failed: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}



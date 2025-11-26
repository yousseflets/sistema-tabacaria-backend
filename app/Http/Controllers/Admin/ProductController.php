<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('admin_user_id')) {
                return redirect()->route('admin.login');
            }
            return $next($request);
        })->except(['importForm', 'import']);
    }

    public function index()
    {
        $query = Product::with(['category','brand'])->orderBy('name', 'asc');

        $q = request('q');
        $categoryId = request('category_id');
        $brandId = request('brand_id');
        $status = request('status'); 
        $priceMin = request('price_min');
        $priceMax = request('price_max');

        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }
        if ($status === 'active') {
            $query->where('active', 1);
        } elseif ($status === 'inactive') {
            $query->where('active', 0);
        }
        if ($priceMin !== null && $priceMin !== '') {
            $min = $this->parseLocalizedCurrency($priceMin);
            if ($min !== null) {
                $query->where('price', '>=', $min);
            }
        }
        if ($priceMax !== null && $priceMax !== '') {
            $max = $this->parseLocalizedCurrency($priceMax);
            if ($max !== null) {
                $query->where('price', '<=', $max);
            }
        }

        $products = $query->paginate(5)->appends(request()->query());

        $categories = Category::orderBy('nome')->get();
        $brands = Brand::orderBy('nome')->get();

        return view('admin.products.index', compact('products','categories','brands'));
    }

    public function create()
    {
        $categories = Category::orderBy('nome')->get();
        $brands = Brand::orderBy('nome')->get();
        return view('admin.products.create', compact('categories','brands'));
    }

    public function importForm()
    {

        return view('admin.products.import');
    }

    public function import(Request $request)
    {
        $uploaded = $request->file('file');
        Log::info('Import upload info', [
            'originalName' => $uploaded->getClientOriginalName(),
            'size' => $uploaded->getSize(),
            'mime' => $uploaded->getClientMimeType(),
        ]);


        $ext = strtolower($uploaded->getClientOriginalExtension() ?? '');
        if (in_array($ext, ['xlsx', 'xls']) && !class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
            return back()->with('error', 'Arquivo Excel (.xlsx/.e o pacote Maatwebsite/Excel não está instalado. Por favor envie um CSV ou instale o pacote.');
        }

        $path = $request->file('file')->store('imports');
        $importErrors = [];
        Log::info('Import request received', ['file' => $path, 'remote_ip' => request()->ip()]);

        // If Maatwebsite Excel is available, use it. Otherwise fall back to a simple CSV parser.
        if (class_exists(\Maatwebsite\Excel\Facades\Excel::class) && class_exists(ProductsImport::class)) {
            $import = new ProductsImport();

            try {
                // prefer passing the UploadedFile directly to avoid issues with generated storage names
                $uploadedFile = $request->file('file');
                Log::info('Using uploaded file for maatwebsite import', ['realPath' => $uploadedFile->getRealPath()]);
                Excel::import($import, $uploadedFile);
            } catch (\Exception $e) {
                // ensure temp stored file is removed if present
                Storage::delete($path);
                return back()->with('error', 'Erro ao importar: ' . $e->getMessage());
            }

            Storage::delete($path);

            if (!empty($import->errors)) {
                return redirect()->route('admin.products.import.form')
                    ->with('import_errors', $import->errors)
                    ->with('success', 'Importação finalizada com algumas falhas.');
            }

            return redirect()->route('admin.products.index')->with('success', 'Importação finalizada. Produtos foram adicionados/atualizados.');
        }

        // Fallback CSV import (no maatwebsite installed)
        $fullPath = storage_path('app/' . $path);
            try {
            if (!file_exists($fullPath)) {
                Storage::delete($path);
                return back()->with('error', 'Arquivo não encontrado após upload.');
            }

            $handle = fopen($fullPath, 'r');
            if (!$handle) {
                Storage::delete($path);
                return back()->with('error', 'Não foi possível abrir o arquivo para leitura.');
            }

            // read header
            $headerLine = fgets($handle);
            $delimiter = strpos($headerLine, ';') !== false ? ';' : ',';
            $headers = array_map(function ($h) {
                $h = mb_strtolower(trim($h));
                if (substr($h, 0, 3) === "\xEF\xBB\xBF") {
                    $h = substr($h, 3);
                }
                return $h;
            }, str_getcsv($headerLine, $delimiter));

            $rowIndex = 1;
            $linesRead = 0;
            while (($line = fgets($handle)) !== false) {
                $rowIndex++;
                $linesRead++;
                $parts = str_getcsv($line, $delimiter);
                if (!$parts || count($parts) === 0) continue;

                $data = [];
                foreach ($headers as $i => $h) {
                    $data[$h] = isset($parts[$i]) ? trim($parts[$i]) : null;
                }

                $name = $data['name'] ?? $data['nome'] ?? null;
                $price = $data['price'] ?? $data['preco'] ?? null;
                $quantity = $data['quantity'] ?? $data['quantidade'] ?? 0;
                $categoryName = $data['category'] ?? $data['categoria'] ?? null;
                $brandName = $data['brand'] ?? $data['marca'] ?? null;

                if (!$name || !$price) {
                    $importErrors[] = ['row' => $rowIndex, 'errors' => ['nome ou preço ausente'], 'data' => $data];
                    continue;
                }

                // encode fix (try UTF-8, fallback CP1252 -> UTF-8)
                if (!mb_check_encoding($name, 'UTF-8')) $name = mb_convert_encoding($name, 'UTF-8', 'CP1252');
                if (!mb_check_encoding($categoryName, 'UTF-8') && $categoryName) $categoryName = mb_convert_encoding($categoryName, 'UTF-8', 'CP1252');
                if (!mb_check_encoding($brandName, 'UTF-8') && $brandName) $brandName = mb_convert_encoding($brandName, 'UTF-8', 'CP1252');

                // normalize price
                $price = str_replace([' ', 'R$', '$'], ['', '', ''], $price);
                $price = str_replace(',', '.', $price);
                $price = floatval($price);

                // require existing category and brand — do not create automatically
                $categoryId = null;
                if ($categoryName) {
                    $category = \App\Models\Category::where('nome', trim($categoryName))->first();
                    if (!$category) {
                        $importErrors[] = ['row' => $rowIndex, 'errors' => ["Categoria '".trim($categoryName)."' não encontrada"], 'data' => $data];
                        continue;
                    }
                    $categoryId = $category->id;
                }
                $brandId = null;
                if ($brandName) {
                    $brand = \App\Models\Brand::where('nome', trim($brandName))->first();
                    if (!$brand) {
                        $importErrors[] = ['row' => $rowIndex, 'errors' => ["Marca '".trim($brandName)."' não encontrada"], 'data' => $data];
                        continue;
                    }
                    $brandId = $brand->id;
                }

                try {
                    \App\Models\Product::updateOrCreate(
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
                } catch (\Throwable $e) {
                    $importErrors[] = ['row' => $rowIndex, 'errors' => [$e->getMessage()], 'data' => $data];
                }
            }

            fclose($handle);
            Storage::delete($path);
            Log::info('Import finished (web fallback)', ['file' => $fullPath, 'lines' => $linesRead, 'errors' => count($importErrors)]);

            if (!empty($importErrors)) {
                return redirect()->route('admin.products.import.form')
                    ->with('import_errors', $importErrors)
                    ->with('success', 'Importação finalizada com algumas falhas.');
            }

            return redirect()->route('admin.products.index')->with('success', 'Importação finalizada. Produtos foram adicionados/atualizados.');
        } catch (\Throwable $e) {
            Storage::delete($path);
            Log::error('Import failed (web fallback)', ['file' => $fullPath, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Erro ao importar: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        // handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/products', 'public');
            $data['image'] = $path;
        }

        // default active as 1
        $data['active'] = 1;
        $data['quantity'] = $data['quantity'] ?? 0;

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produto criado.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nome')->get();
        $brands = Brand::orderBy('nome')->get();
        return view('admin.products.edit', compact('product', 'categories','brands'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'price' => 'required|numeric',
            'quantity' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/products', 'public');
            $data['image'] = $path;
        }

        // ensure quantity exists
        $data['quantity'] = $data['quantity'] ?? ($product->quantity ?? 0);

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado.');
    }

    public function toggle(Product $product)
    {
        $product->active = $product->active ? 0 : 1;
        $product->save();

        $message = $product->active ? 'Produto ativado com sucesso.' : 'Produto desativado com sucesso.';

        return back()->with('success', $message);
    }

    /**
     * Parse a localized currency string (e.g. "R$ 1.234,56" or "1.234,56" or "1234.56")
     * and return a float value or null if it cannot be parsed.
     *
     * @param string|null $value
     * @return float|null
     */
    private function parseLocalizedCurrency($value)
    {
        if ($value === null) return null;
        $v = trim((string)$value);
        if ($v === '') return null;

        // Remove currency symbols and whitespace, keep digits, dots, commas and minus
        $v = preg_replace('/[^0-9,\.\-]/u', '', $v);
        if ($v === '') return null;

        // If contains both '.' and ',' assume '.' = thousand sep, ',' = decimal
        if (strpos($v, '.') !== false && strpos($v, ',') !== false) {
            $v = str_replace('.', '', $v);
            $v = str_replace(',', '.', $v);
        } else {
            // Only comma present -> decimal separator in pt-BR
            if (strpos($v, ',') !== false) {
                $v = str_replace(',', '.', $v);
            }
            // Only dots present -> assume standard decimal or no thousand sep
        }

        // Final cleanup: remove multiple dots except the last (defensive)
        if (substr_count($v, '.') > 1) {
            $parts = explode('.', $v);
            $dec = array_pop($parts);
            $v = implode('', $parts) . '.' . $dec;
        }

        $num = floatval($v);
        return $num;
    }
}

<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Row;

class ProductsImport implements WithHeadingRow, OnEachRow, WithCustomCsvSettings
{
    // collect errors per row
    public $errors = [];

    private function ensureUtf8($s)
    {
        if ($s === null) return null;
        $s = trim($s);
        if ($s === '') return $s;
        if (mb_check_encoding($s, 'UTF-8')) return $s;
        return mb_convert_encoding($s, 'UTF-8', 'CP1252');
    }

    /**
     * Process each row, validate and create/update product.
     */
    public function onRow(Row $row)
    {
        $index = $row->getIndex();
        $raw = $row->toArray();
        $data = [];
        foreach ($raw as $k => $v) {
            $key = mb_strtolower($k);
            // remove UTF-8 BOM if present
            if (substr($key, 0, 3) === "\xEF\xBB\xBF") {
                $key = substr($key, 3);
            }
            $key = trim($key);
            $data[$key] = $v;
        }

        // normalize keys - accept PT/EN headers
        $categoryName = $this->ensureUtf8($data['category'] ?? $data['categoria'] ?? null);
        $brandName = $this->ensureUtf8($data['brand'] ?? $data['marca'] ?? null);
        $name = $this->ensureUtf8($data['name'] ?? $data['nome'] ?? null);
        $price = $this->ensureUtf8($data['price'] ?? $data['preco'] ?? null);
        $quantity = $this->ensureUtf8($data['quantity'] ?? $data['quantidade'] ?? null);

        // prepare validation
        $validator = Validator::make([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'active' => 1,
            'category' => $categoryName,
            'brand' => $brandName,
        ], [
            'name' => 'required|string|max:255',
            'price' => [
                'required',
                'regex:/^\d{1,12}(?:[\.,]\d{1,2})?$/'
            ],
            'quantity' => 'nullable|integer|min:0|max:1000000',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
        ], [
            'price.regex' => 'O campo preço deve ser um número válido (ex.: 10, 10.00 ou 1000,50).',
        ]);

        if ($validator->fails()) {
            $this->errors[] = [
                'row' => $index,
                'data' => $data,
                'errors' => $validator->errors()->all(),
            ];
            return; // skip this row
        }

        // normalize values
        $price = is_numeric($price) ? $price : str_replace([',', ' '], ['.', ''], $price);
        $price = floatval($price);
        $quantity = intval($quantity ?? 0);
        // sempre marcar como ativo ao importar (1)
        $active = 1;

        // find or create category/brand by nome
        $categoryId = null;
        if ($categoryName) {
            // do not create categories automatically; require existing categories
            $category = Category::where('nome', trim($categoryName))->first();
            if (!$category) {
                $this->errors[] = [
                    'row' => $index,
                    'data' => $data,
                    'errors' => ["Categoria '".trim($categoryName)."' não encontrada"],
                ];
                return; // skip this row
            }
            $categoryId = $category->id;
        }

        $brandId = null;
        if ($brandName) {
            // do not create brands automatically; require existing brands
            $brand = Brand::where('nome', trim($brandName))->first();
            if (!$brand) {
                $this->errors[] = [
                    'row' => $index,
                    'data' => $data,
                    'errors' => ["Marca '".trim($brandName)."' não encontrada"],
                ];
                return; // skip this row
            }
            $brandId = $brand->id;
        }

        $productData = [
            'image' => null,
            'price' => $price,
            'quantity' => $quantity,
            'active' => $active,
            'category_id' => $categoryId,
            'brand_id' => $brandId,
        ];

        $nameKey = $name ?: 'Produto sem nome';
        Product::updateOrCreate(['name' => $nameKey], array_merge(['name' => $nameKey], $productData));
    }

    /**
     * Ensure CSV delimiter and encoding are handled (Excel in BR often uses `;`).
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'input_encoding' => 'UTF-8',
        ];
    }

    // normalizeActive removida: import define ativo = 1 por padrão
}

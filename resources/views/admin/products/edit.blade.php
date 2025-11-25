@extends('admin.layout')

@section('title', 'Editar Produto')

@section('content')
    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="max-w-2x2 bg-white p-6 rounded shadow" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Categoria</label>
            <select name="category_id" class="w-full border rounded px-3 py-2">
                <option value="">Selecione</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Marca</label>
            <select name="brand_id" class="w-full border rounded px-3 py-2">
                <option value="">Selecione</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>{{ $brand->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Imagem</label>
            @if($product->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded" />
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Preço</label>
            <input type="text" name="price" value="{{ number_format((float) old('price', $product->price ?? 0), 2, ',', '.') }}" required class="w-full border rounded px-3 py-2 js-currency" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Quantidade</label>
            <input type="number" name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" min="0" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="flex justify-end">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Atualizar</button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function formatBRFromDigits(digits) {
        if (!digits || digits.length === 0) digits = '0';
        var cents = parseInt(digits, 10);
        if (isNaN(cents)) cents = 0;
        var num = cents / 100;
        return num.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function formatBR(value) {
        var digits = String(value).replace(/\D/g, '');
        return formatBRFromDigits(digits);
    }

    function toNumericValue(formatted) {
        // Convert formatted string to numeric value robustly by extracting digits (cents)
        if (!formatted) return '0.00';
        var digits = String(formatted).replace(/\D/g, '');
        if (!digits) digits = '0';
        var cents = parseInt(digits, 10);
        if (isNaN(cents)) cents = 0;
        var num = (cents / 100).toFixed(2);
        return num;
    }

    document.querySelectorAll('.js-currency').forEach(function (el) {
        let v = el.value || '';
        let digits = String(v).replace(/\D/g, '');
        el.value = formatBRFromDigits(digits);

        el.addEventListener('input', function (e) {
            var raw = el.value;
            el.value = formatBR(raw);
            el.selectionStart = el.selectionEnd = el.value.length;
        });
    });

    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            form.querySelectorAll('.js-currency').forEach(function (el) {
                el.value = toNumericValue(el.value);
            });
        });
    });
});
</script>
@endsection

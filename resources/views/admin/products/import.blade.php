@extends('admin.layout')

@section('title', 'Importar Produtos')

@section('content')
    <div class="max-w-2x2 bg-white p-6 rounded shadow">
        <h2 class="text-lg font-medium mb-4">Importar Produtos (Excel/CSV)</h2>

        <p class="text-sm mb-4">Download de exemplo: <a href="{{ asset('importar-produtos.csv') }}" class="text-blue-600 underline">Baixar planilha de exemplo (CSV)</a></p>

        @if(session('import_errors'))
            <div class="mb-4 bg-red-50 border border-red-200 p-4 rounded">
                <h3 class="font-medium text-red-700">Erros na importação (algumas linhas não foram importadas)</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach(session('import_errors') as $err)
                        <li>Linha <strong>{{ $err['row'] }}</strong>: {{ implode('; ', $err['errors']) }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Arquivo (XLSX, XLS, CSV)</label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full border rounded px-3 py-2" />
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Importar</button>
            </div>
        </form>
    </div>
@endsection

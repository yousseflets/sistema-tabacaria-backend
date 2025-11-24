@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <div class="text-sm text-gray-500">Total de produtos</div>
            <div class="text-2xl font-bold">{{ $total }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-sm text-gray-500">Ativos</div>
            <div class="text-2xl font-bold text-green-600">{{ $active }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-sm text-gray-500">Inativos</div>
            <div class="text-2xl font-bold text-red-600">{{ $inactive }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.products.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Ir para Produtos</a>
    </div>
@endsection

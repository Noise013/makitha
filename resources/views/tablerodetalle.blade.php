<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del Tablero: {{ $tablero->nombre_tablero }}
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="bg-white shadow rounded p-6">
            <p><strong>ID:</strong> {{ $tablero->id }}</p>
            <p><strong>Nombre:</strong> {{ $tablero->nombre_tablero }}</p>
            <p><strong>Evento ID:</strong> {{ $tablero->evento_id }}</p>
            <p><strong>Fecha de creación:</strong> {{ $tablero->created_at->format('d/m/Y') }}</p>
        </div>
        
    </div>
    
</x-app-layout>

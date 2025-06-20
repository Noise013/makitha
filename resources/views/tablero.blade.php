<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Historial de tableros</h2>
                        
                        <a href="{{ route('tableros.crear') }}" class="btnPrimary">Crear nuevo tablero</a>

                    </div>

                    <ul class="space-y-2">
                        @forelse($tableros as $tablero)
                            <li class="border p-4 rounded hover:bg-gray-50 listRep">
                                <a href="{{ route('tableros.detalle', ['id' => $tablero->id]) }}" class="text-blue-600 hover:underline">
                                    {{ $tablero->nombre_tablero ?? 'Sin nombre' }}
                                    <span class="fechaReporte">Creado el {{ $tablero->created_at->format('d-m-Y') }}</span>
                                </a>
                            </li>
                        @empty
                            <li class="text-gray-500">No hay tableros aún.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Evento: {{ $evento->nombre_archivo ?? 'Sin nombre' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><strong>ID:</strong> {{ $evento->id }}</p>
                    <p><strong>Nombre:</strong> {{ $evento->nombre_archivo ?? 'Sin nombre' }}</p>
                    <p><strong>Fecha de creación:</strong> {{ $evento->created_at->format('Y-m-d H:i') }}</p>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Movimientos del evento</h3>

                    @if($movimientos->isEmpty())
                        <p>No hay movimientos para este evento.</p>
                    @else
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2">Fecha</th>
                                    <th class="border px-4 py-2">Descripción</th>
                                    <th class="border px-4 py-2">FEAT BUSINESS</th>
                                    <th class="border px-4 py-2">BIG BROTHERS</th>
                                    <th class="border px-4 py-2">G&A</th>
                                    <th class="border px-4 py-2">CORPORATIVO</th>
                                    <th class="border px-4 py-2">Importe</th>

                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movimientos as $mov)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $mov->fecha }}</td>
                                        <td class="border px-4 py-2">{{ $mov->descripcion }}</td>
                                        <td class="border px-4 py-2">{{ $mov->feat_business }}</td>
                                        <td class="border px-4 py-2">{{ $mov->big_brothers }}</td>
                                        <td class="border px-4 py-2">{{ $mov->g_and_a }}</td>
                                        <td class="border px-4 py-2">{{ $mov->corporativo }}</td>
                                         <td class="border px-4 py-2">{{ $mov->importe }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

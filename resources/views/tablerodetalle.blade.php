<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del Tablero: {{ $tablero->nombre_tablero }}
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="bg-white shadow rounded p-6 mb-10">
            <p><strong>ID:</strong> {{ $tablero->id }}</p>
            <p><strong>Nombre:</strong> {{ $tablero->nombre_tablero }}</p>
            <p><strong>Evento ID:</strong> {{ $tablero->evento_id }}</p>
            <p><strong>Fecha de creación:</strong> {{ $tablero->created_at->format('d/m/Y') }}</p>
        </div>

        {{-- Tabla 1: Acumulado del mes anterior --}}
        <div class="bg-white shadow rounded p-6 mb-10">
            <h3 class="text-lg font-semibold mb-4">Acumulado del mes anterior</h3>
            <table class="table-auto w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Cliente</th>
                        <th class="border px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($acumuladoMesAnterior as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->cliente }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Tabla 2: Mes dinámico --}}
        <div class="bg-white shadow rounded p-6 mb-10">
            <h3 class="text-lg font-semibold mb-4">{{ $ultimoMes }}</h3>
            <table class="table-auto w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Real</th>
                        <th class="border px-4 py-2">Plan</th>
                        <th class="border px-4 py-2">VS Plan</th>
                        <th class="border px-4 py-2">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        @php
                            $item = $mesDinamicoCalculado[$cliente] ?? (object)[
                                'real' => 0,
                                'plan' => 0,
                                'vs_plan' => 0,
                                'porcentaje' => 0,
                            ];
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ number_format($item->real, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->plan ?? 0, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->vs_plan ?? 0, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->porcentaje ?? 0, 2) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Tabla 3: Siguiente mes --}}
        <div class="bg-white shadow rounded p-6 mb-10">
            <h3 class="text-lg font-semibold mb-4 mt-10">Siguiente Mes</h3>
            <table class="table-auto w-full border border-gray-300 mb-10">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Resultado Acumulado</th>
                        <th class="border px-4 py-2 text-left">Plan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td class="border px-4 py-2">—</td>
                            <td class="border px-4 py-2">—</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Tabla 4: Acumulado no alcanzado --}}
        <div class="bg-white shadow rounded p-6 mb-10">
            <h3 class="text-lg font-semibold mb-4">Acumulado no alcanzado</h3>
            <table class="table-auto w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Cliente</th>
                        <th class="border px-4 py-2">Resultado acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($acumuladoNoAlcanzado as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->cliente }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->resultado_acumulado, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Tabla 5: Acciones a tomar --}}
        <div class="bg-white shadow rounded p-6 mb-10">
            <h3 class="text-lg font-semibold mb-4">Acciones a tomar</h3>
            <table class="table-auto w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Servicio</th>
                        <th class="border px-4 py-2">Propuesta</th>
                        <th class="border px-4 py-2">Monto</th>
                        <th class="border px-4 py-2">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accionesATomar as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item->servicio }}</td>
                            <td class="border px-4 py-2">{{ $item->propuesta }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->monto, 2) }}</td>
                            <td class="border px-4 py-2">
                                {{ $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- 1er porcentaje de ejecucion --}}
        <div class="bg-white shadow rounded p-6 mb-10"> 

            <h4 class="text-lg font-semibold mb-4">Porcentaje de Ejecución</h4>
            
            {{-- Porcentaje y barra --}}
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-semibold text-blue-600"> 75% </span> {{-- Cambiar por el valor dinámico más adelante: {{ $porcentajeFinal }}% --}} 
                    <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                        <div class="bg-blue-500 h-full" style="width: 75%;"></div> {{-- Cambiar width según porcentaje D: --}}
                    </div>
                </div>
            </div>
            
            {{-- Tabla chiquita --}}
            <div>
                <table class="table-auto w-full border border-gray-300">
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">META ANUAL</td>
                            <td class="border px-4 py-2">—</td>  {{-- Datos vacios por ahora --}}
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">EJECUTADO A LA FECHA</td>
                            <td class="border px-4 py-2">—</td> {{-- Datos vacios por ahora --}}
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">DIFERENCIA</td>
                            <td class="border px-4 py-2">—</td> {{-- Datos vacios por ahora --}}
                        </tr>
                    </tbody>
                </table>
            </div>  
        </div>
        {{-- 2do porcentaje de ejecucion --}}
        <div class="bg-white shadow rounded p-6 mb-10"> 

            <h4 class="text-lg font-semibold mb-4">Porcentaje de Ejecución</h4>
            
            {{-- Porcentaje y barra --}}
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-semibold text-blue-600"> 75% </span> {{-- Cambiar por el valor dinámico más adelante: {{ $porcentajeFinal }}% --}} 
                    <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                        <div class="bg-blue-500 h-full" style="width: 75%;"></div> {{-- Cambiar width según porcentaje D: --}}
                    </div>
                </div>
            </div>
            
            {{-- Tabla chiquita --}}
            <div>
                <table class="table-auto w-full border border-gray-300">
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">META ENE-MAY</td>
                            <td class="border px-4 py-2">—</td>  {{-- Datos vacios por ahora --}}
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">EJECUTADO A LA FECHA</td>
                            <td class="border px-4 py-2">—</td> {{-- Datos vacios por ahora --}}
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">DIFERENCIA</td>
                            <td class="border px-4 py-2">—</td> {{-- Datos vacios por ahora --}}
                        </tr>
                    </tbody>
                </table>
            </div>  
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tablero: {{ $tablero->nombre_tablero }} ({{ $tablero->created_at->format('d/m/Y') }})
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('tableros.guardarDatos', ['id' => $tablero->id]) }}">
                @csrf

                {{-- Primera tabla --}}
                <h3 class="text-lg font-semibold mb-4">Acumulado del mes anterior</h3>
                <table class="table-auto w-full border border-gray-300 mb-10">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Cliente</th>
                            <th class="border px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border px-4 py-2">{{ $cliente }}</td>
                                <td class="border px-4 py-2">
                                    <input type="number" 
                                           name="acumulado_mes_anterior[{{ $cliente }}][total]" 
                                           step="0.01" 
                                           class="w-full border rounded p-1"
                                           value="{{ old('acumulado_mes_anterior.' . $cliente . '.total') }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Segunda tabla --}}
                <h3 class="text-lg font-semibold mb-4">{{ $ultimoMes }}</h3>
                <table class="table-auto w-full border border-gray-300 mb-10">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Real</th>
                            <th class="border px-4 py-2 text-left">Plan</th>
                            <th class="border px-4 py-2 text-left">VS Plan</th>
                            <th class="border px-4 py-2 text-left">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border px-4 py-2">
                                    {{ number_format($reales[$cliente] ?? 0, 2) }}
                                    <input type="hidden" 
                                        name="mes_dinamico[{{ $cliente }}][real]" 
                                        value="{{ $reales[$cliente] ?? 0 }}">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="number" 
                                        name="mes_dinamico[{{ $cliente }}][plan]" 
                                        step="0.01" 
                                        class="w-full border rounded p-1"
                                        value="{{ old('mes_dinamico.' . $cliente . '.plan') }}" />
                                </td>
                                <td class="border px-4 py-2">—</td>
                                <td class="border px-4 py-2">—</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Tercera tabla --}}
                <h3 class="text-lg font-semibold mb-4">Acumulado no alcanzado</h3>
                <table class="table-auto w-full border border-gray-300 mb-10">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Cliente</th>
                            <th class="border px-4 py-2 text-left">Resultado Acumulado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border px-4 py-2">{{ $cliente }}</td>
                                <td class="border px-4 py-2">
                                    <input type="number" 
                                           name="acumulado_no_alcanzado[{{ $cliente }}][resultado]" 
                                           step="0.01" 
                                           class="w-full border rounded p-1"
                                           value="{{ old('acumulado_no_alcanzado.' . $cliente . '.resultado') }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Cuarta tabla --}}
                <h3 class="text-lg font-semibold mb-4">Acciones a Tomar</h3>
                <table class="table-auto w-full border border-gray-300 mb-10">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Servicio</th>
                            <th class="border px-4 py-2 text-left">Propuesta</th>
                            <th class="border px-4 py-2 text-left">Monto</th>
                            <th class="border px-4 py-2 text-left">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border px-4 py-2">
                                    <input type="text" 
                                           name="acciones_a_tomar[{{ $cliente }}][servicio]" 
                                           class="w-full border rounded p-1"
                                           value="{{ old('acciones_a_tomar.' . $cliente . '.servicio') }}" />
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="text" 
                                           name="acciones_a_tomar[{{ $cliente }}][propuesta]" 
                                           class="w-full border rounded p-1"
                                           value="{{ old('acciones_a_tomar.' . $cliente . '.propuesta') }}" />
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="number" 
                                           step="0.01" 
                                           name="acciones_a_tomar[{{ $cliente }}][monto]" 
                                           class="w-full border rounded p-1"
                                           value="{{ old('acciones_a_tomar.' . $cliente . '.monto') }}" />
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="date" 
                                           name="acciones_a_tomar[{{ $cliente }}][fecha]" 
                                           class="w-full border rounded p-1"
                                           value="{{ old('acciones_a_tomar.' . $cliente . '.fecha') }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Botón para guardar estos datos --}}
                <div class="mt-6 text-right">
                    <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
                        Guardar datos
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
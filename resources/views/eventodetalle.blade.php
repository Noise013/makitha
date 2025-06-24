<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte: {{ $evento->nombre_archivo ?? 'Sin nombre' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p><strong>Nombre:</strong> {{ $evento->nombre_archivo ?? 'Sin nombre' }}</p>
                    <p><strong>Fecha de creación:</strong> {{ $evento->created_at->format('d-m-Y') }}</p>

                    <hr class="my-4">

                    <h3 class="text-lg font-semibold mb-2">Resumen de ingresos</h3>

                    @if($movimientos->isEmpty())
                        <p>No hay movimientos para este evento.</p>
                    @else

                    <div class="titleResumenReporte">
                        <h1>MENSUAL</h1>
                    </div>

                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="border py-2 headTable"></th>
                                    <th class="border py-2 headTable">ENE</th>
                                    <th class="border py-2 headTable">FEB</th>
                                    <th class="border py-2 headTable">MAR</th>
                                    <th class="border py-2 headTable">ABR</th>
                                    <th class="border py-2 headTable">MAY</th>
                                    <th class="border py-2 headTable">JUN</th>
                                    <th class="border py-2 headTable">JUL</th>
                                    <th class="border py-2 headTable">AGO</th>
                                    <th class="border py-2 headTable">SEP</th>
                                    <th class="border py-2 headTable">OCT</th>
                                    <th class="border py-2 headTable">NOV</th>
                                    <th class="border py-2 headTable">DIC</th>
                                    <th class="border py-2 headTable">TOTAL</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border py-2 contentTableTitle">META</td>
                                    @foreach($pMensuales as $p)
                                        <td class="border py-2 contentTable">
                                            Q.{{ fmod($p->proyeccion, 1) == 0 
                                                ? number_format($p->proyeccion, 0, '.', ',') 
                                                : number_format($p->proyeccion, 2, '.', ',') }}
                                        </td> 
                                    @endforeach
                                    <td class="border py-2 font-bold text-right pr-2 contentTable">
                                        Q.{{ fmod($totalProyeccion, 1) == 0 
                                            ? number_format($totalProyeccion, 0, '.', ',') 
                                            : number_format($totalProyeccion, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <td class="border py-2 contentTableTitle">EJECUTADO</td>
                                    @foreach($ejecutadoPorMesCompletos as $mes => $monto)
                                        <td class="border py-2 contentTable">Q.{{ fmod($monto, 1) == 0 ? number_format($monto, 0, '.', ',') : number_format($monto, 2, '.', ',') }}</td>
                                    @endforeach
                                    <td class="border py-2 contentTable">
                                        Q.{{ number_format(array_sum($ejecutadoPorMesCompletos), 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border py-2 contentTableTitle">VS META</td>
                                    @php
                                        $totalVsMeta = 0;
                                    @endphp
                                    @foreach ($vsMeta as $mes => $valor)
                                        @php
                                            $totalVsMeta += $valor;
                                        @endphp
                                        <td class="border py-2 contentTable">
                                            Q.{{ fmod($valor, 1) == 0 ? number_format($valor, 0, '.', ',') : number_format($valor, 2, '.', ',') }}
                                        </td>
                                    @endforeach
                                    <td class="border py-2 contentTable font-bold">
                                        Q.{{ fmod($totalVsMeta, 1) == 0 ? number_format($totalVsMeta, 0, '.', ',') : number_format($totalVsMeta, 2, '.', ',') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

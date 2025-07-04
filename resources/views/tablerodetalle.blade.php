<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalle del Tablero:
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="bg-white shadow rounded p-6 mb-10">
            <!-- <p><strong>ID:</strong> {{ $tablero->id }}</p> -->
            <p><strong>Nombre:</strong> {{ $tablero->nombre_tablero }}</p>
            <p><strong>Reporte:</strong> {{ $reporte->nombre_archivo ?? 'Sin nombre' }}</p>
            <p><strong>Fecha de creación:</strong> {{ $tablero->created_at->format('d-m-Y') }}</p>
        </div>
        <div class="tableResumeTablero flex flex-col md:flex-row gap-6 mb-10">
            {{-- Tabla 1: Acumulado Real del mes anterior --}}
            <div class="w-full md:w-[60%] bg-white shadow rounded p-6 mb-10">
                <div class="titleResumenReporte">
                    <h3>ACUMULADO REAL MES ANTERIOR</h3>
                </div>
                <table class="table-auto w-full border border-gray-300 tableReporteTablero">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Cliente</th>
                            <th class="border px-4 py-2">Resultado acumulado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border px-4 py-2">{{ $cliente }}</td>
                                <td class="border px-4 py-2">
                                     {{ number_format($acumuladoAnterior[$cliente] ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Tabla 2: Mes dinámico --}}
            <div class="w-full md:w-[60%] bg-white shadow rounded p-6 mb-10">
                <div class="titleResumenReporte">
                    <h3 class="text-lg font-semibold">{{ $ultimoMes }}</h3>
                </div>
                <table class="table-auto w-full border border-gray-300 tableReporteTablero">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Cliente</th>
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
                                <td class="border px-4 py-2">{{ $cliente }}</td>
                                <td class="border px-4 py-2">Q. {{ number_format($item->real, 2) }}</td>
                                <td class="border px-4 py-2">Q. {{ number_format($item->plan ?? 0, 2) }}</td>
                                <td class="border px-4 py-2">Q. {{ number_format($item->vs_plan ?? 0, 2) }}</td>
                                <td class="border px-4 py-2 
                                    {{ $item->porcentaje >= 0 ? 'text-green-600' : ($item->porcentaje < 0 ? 'text-red-600' : 'text-gray-800') }}">
                                    {{ intval($item->porcentaje ?? 0) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Tabla 3: Siguiente mes // Resultado Acumulado hasta la fecha--}}
       <div class="tableResumeTablero bg-white shadow rounded p-6 mb-10">
            <div class="titleResumenReporte">
                <h3 class="text-lg font-semibold">Resultado Acumulado hasta la fecha</h3>
            </div>
            <table class="table-auto w-full border border-gray-300 mb-10 tableReporteTablero">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Cliente</th>
                        <th class="border px-4 py-2 text-left">Resultado Acumulado</th>
                        <th class="border px-4 py-2 text-left">Plan Siguiente Mes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        @php
                            $resultado = $acumuladoMesAnterior->firstWhere('cliente', $cliente);
                            $plan = $mesDinamicoCalculado[$cliente] ?? null;
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ $cliente }}</td>
                            <td class="border px-4 py-2">
                                {{ number_format($acumuladoTotal[$cliente] ?? 0, 2) }}
                            </td>
                            <td class="border px-4 py-2">Q. {{ number_format($plan->plan ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!--
        <div class="tableResumeTablero grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            {{-- Tabla 4: Acumulado no alcanzado / hay que sacarlo --}}
            <div class="bg-white shadow rounded p-6 mb-10">
                <div class="titleResumenReporte">
                    <h3 class="text-lg font-semibold">Acumulado no alcanzado</h3>
                </div>
                <table class="table-auto w-full border border-gray-300 tableReporteTablero">
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
                                <td class="border px-4 py-2">Q. {{ number_format($item->resultado_acumulado, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>-->
            {{-- Tabla 5: Acciones a tomar --}}
            <div class="bg-white shadow rounded p-6 mb-10">
                <div class="titleResumenReporte">
                    <h3 class="text-lg font-semibold">Acciones a tomar</h3>
                </div>
                <table class="table-auto w-full border border-gray-300 tableReporteTablero">
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
                                <td class="border px-4 py-2">Q. {{ number_format($item->monto, 2) }}</td>
                                <td class="border px-4 py-2">
                                    {{ $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Contenedor de las 2 columnas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

            {{-- 1er porcentaje de ejecución --}}
            <div class="bg-white shadow rounded p-6"> 
                <div class="titleResumenReporte">
                    <h4 class="text-lg font-semibold">Porcentaje de Ejecución anual</h4>
                </div>
                <div class="mb-6">
                    <div class="flex items-center space-x-8 h-[400px]">
                        {{-- Porcentaje a la izquierda --}}
                        <span class="text-2xl font-semibold w-16 text-right" 
                            style="color: {{ $porcentajeAnual > 0 ? 'green' : ($porcentajeAnual < 0 ? 'red' : 'gray') }}">
                            {{ $porcentajeAnual > 0 ? '+' : '' }}{{ $porcentajeAnual }}%
                        </span>

                        {{-- Barra vertical en el centro --}}
                        <div class="relative w-[55px] h-full bg-gray-200 rounded-full overflow-hidden">
                            <div 
                                class="absolute bottom-0 percentBar left-0 w-full" 
                                style="height: {{ min(abs($porcentajeAnual), 100) }}%; background-color: {{ $porcentajeAnual > 0 ? 'green' : ($porcentajeAnual < 0 ? 'red' : 'gray') }}">
                            </div>
                        </div>

                        {{-- Tabla a la derecha --}}
                        <div class="w-full">
                            <table class="table-auto w-full border border-gray-300">
                                <tbody>
                                    <tr>
                                        <td class="border px-4 py-2">META ANUAL</td>
                                        <td class="border px-4 py-2">
                                            Q.{{ fmod($totalProyeccion, 1) == 0 
                                                ? number_format($totalProyeccion, 0, '.', ',') 
                                                : number_format($totalProyeccion, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border px-4 py-2">EJECUTADO A LA FECHA</td>
                                        <td class="border px-4 py-2">
                                            Q.{{ fmod($ejecutadoAFecha, 1) == 0 
                                                ? number_format($ejecutadoAFecha, 0, '.', ',') 
                                                : number_format($ejecutadoAFecha, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border px-4 py-2">DIFERENCIA</td>
                                        <td class="border px-4 py-2">
                                            Q.{{ fmod($diferenciaAnual, 1) == 0 
                                                ? number_format($diferenciaAnual, 0, '.', ',') 
                                                : number_format($diferenciaAnual, 2, '.', ',') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2do porcentaje de ejecución --}}
            <div class="bg-white shadow rounded p-6"> 
                <div class="titleResumenReporte">
                    <h4 class="text-lg font-semibold">Porcentaje de Ejecución a la fecha</h4>
                </div>

                <div class="flex items-center space-x-8 h-[400px]">
                    {{-- Porcentaje a la izquierda --}}
                    <span class="text-2xl font-semibold w-16 text-right" 
                        style="color: {{ $porcentajeAFecha > 0 ? 'green' : ($porcentajeAFecha < 0 ? 'red' : 'gray') }}">
                        {{ $porcentajeAFecha > 0 ? '+' : '' }}{{ $porcentajeAFecha }}%
                    </span>

                    {{-- Barra vertical en el centro --}}
                    <div class="relative w-[55px] h-full bg-gray-200 rounded-full overflow-hidden">
                        <div 
                            class="absolute bottom-0 percentBar left-0 w-full" 
                            style="height: {{ min(abs($porcentajeAFecha), 100) }}%; background-color: {{ $porcentajeAFecha > 0 ? 'green' : ($porcentajeAFecha < 0 ? 'red' : 'gray') }};">
                        </div>
                    </div>

                    {{-- Tabla a la derecha --}}
                    <div class="w-full">
                        <table class="table-auto w-full border border-gray-300">
                            <tbody>
                                <tr>
                                    <td class="border px-4 py-2">META A LA FECHA</td>
                                    <td class="border px-4 py-2">
                                        Q.{{ fmod($metaAFecha, 1) == 0 
                                            ? number_format($metaAFecha, 0, '.', ',') 
                                            : number_format($metaAFecha, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border px-4 py-2">EJECUTADO A LA FECHA</td>
                                    <td class="border px-4 py-2">
                                        Q.{{ fmod($ejecutadoAFecha, 1) == 0 
                                            ? number_format($ejecutadoAFecha, 0, '.', ',') 
                                            : number_format($ejecutadoAFecha, 2, '.', ',') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border px-4 py-2">DIFERENCIA</td>
                                    <td class="border px-4 py-2">
                                        Q.{{ fmod($diferenciaAFecha, 1) == 0 
                                            ? number_format($diferenciaAFecha, 0, '.', ',') 
                                            : number_format($diferenciaAFecha, 2, '.', ',') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

                            

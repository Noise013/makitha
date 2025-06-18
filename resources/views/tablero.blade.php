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
                        {{-- Más adelante, esto se llenará con los tableros --}}
                        <li class="text-gray-500">No hay tableros aún.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- después veo si te disecciono
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tablero') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6 space-y-10">
        {{-- Tabla vertical a la izquierda --}}
        <div style="float: left; margin-right: 40px;">
            <table class="border border-gray-700 border-collapse">
                <thead>
                    <tr>
                        <th class="border border-gray-700 px-4 py-2">Cliente</th>
                        <th class="border border-gray-700 px-4 py-2">Resultado acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 8; $i++)
                        <tr>
                            <td class="border border-gray-700 px-4 py-2">Cliente A</td>
                            <td class="border border-gray-700 px-4 py-2">100</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Tabla principal al centro --}}
        <div style="margin-left: 300px;">
            <table class="border border-gray-700 border-collapse">
                <thead>
                    <tr>
                        <th class="border border-gray-700 px-4 py-2">Servicio</th>
                        <th class="border border-gray-700 px-4 py-2">Propuesta</th>
                        <th class="border border-gray-700 px-4 py-2">Monto</th>
                        <th class="border border-gray-700 px-4 py-2">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-700 px-4 py-2">Servicio A</td>
                        <td class="border border-gray-700 px-4 py-2">Propuesta A</td>
                        <td class="border border-gray-700 px-4 py-2">$1000</td>
                        <td class="border border-gray-700 px-4 py-2">2025-06-01</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-700 px-4 py-2">Servicio B</td>
                        <td class="border border-gray-700 px-4 py-2">Propuesta B</td>
                        <td class="border border-gray-700 px-4 py-2">$2000</td>
                        <td class="border border-gray-700 px-4 py-2">2025-06-02</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Dos tablas de 2x3 al final --}}
        <div style="display: flex; gap: 40px; margin-top: 60px;">
            <table class="border border-gray-700 border-collapse">
                <tr><th class="border border-gray-700 px-4 py-2">Etiqueta 1</th><td class="border border-gray-700 px-4 py-2">Valor 1</td></tr>
                <tr><th class="border border-gray-700 px-4 py-2">Etiqueta 2</th><td class="border border-gray-700 px-4 py-2">Valor 2</td></tr>
                <tr><th class="border border-gray-700 px-4 py-2">Etiqueta 3</th><td class="border border-gray-700 px-4 py-2">Valor 3</td></tr>
            </table>

            <table class="border border-gray-700 border-collapse">
                <tr><th class="border border-gray-700 px-4 py-2">Etiqueta A</th><td class="border border-gray-700 px-4 py-2">Valor A</td></tr>
                <tr><th class="border border-gray-700 px-4 py-2">Etiqueta B</th><td class="border border-gray-700 px-4 py-2">Valor B</td></tr>
                <tr><th class="border border-gray-700 px-4 py-2">Etiqueta C</th><td class="border border-gray-700 px-4 py-2">Valor C</td></tr>
            </table>
        </div>
    </div>
</x-app-layout> -->

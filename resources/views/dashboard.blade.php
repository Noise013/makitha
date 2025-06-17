<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <div class="filtroByYear">
                <form method="GET" action="{{ route('dashboard') }}" class="mb-0">
                    <label for="year" class="mr-2 text-sm font-medium text-gray-700">Filtrar por año:</label>
                    <select name="year" id="year" class="yearFilter border rounded px-2 py-1" onchange="this.form.submit()">
                        <option value="all" {{ $year === 'all' || !$year ? 'selected' : '' }}>Todos</option>
                        @foreach ($aniosDisponibles as $año)
                            <option value="{{ $año }}" {{ $year == $año ? 'selected' : '' }}>{{ $año }}</option>
                        @endforeach
                    </select>
                </form> 
            </div>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="general-info">
                        <div class="slot-info">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2 relative">
                                Ingresos totales
                                <div class="relative cursor-pointer">
                                    <span class="group text-white bg-gray-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">
                                        ?
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-72 bg-white text-gray-700 text-sm p-3 rounded-md shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                                            El total de ingresos representa la sumatoria de todas las facturas ingresadas en todos los archivos subidos desde el inicio del año fiscal seleccionado o la sumatoria total de todos los años registrados.
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3 bg-white rotate-45 arrow-tooltip"></div>
                                        </div>
                                    </span>
                                </div>
                            </h2>
                            <h1>
                                <span>GTQ</span>
                                {{ number_format($totalImporte, 2, '.', ',') }}
                            </h1>
                        </div>
                        <div class="slot-info">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                                Facturas ingresadas
                                <div class="relative cursor-pointer">
                                    <span class="group text-white bg-gray-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">
                                        ?
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-72 bg-white text-gray-700 text-sm p-3 rounded-md shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                                            El total de las facturas ingresadas representa la sumatoria de todas las facturas ingresadas desde el inicio del año fiscal seleccionado o la sumatoria total de todos los años registrados.
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3 bg-white rotate-45 arrow-tooltip"></div>
                                        </div>
                                    </span>
                                </div>
                            </h2>
                            <h1>{{ number_format($totalFilas) }}</h1>
                        </div>
                        <div class="slot-info">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                                Clientes
                                <div class="relative cursor-pointer">
                                    <span class="group text-white bg-gray-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">
                                        ?
                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-72 bg-white text-gray-700 text-sm p-3 rounded-md shadow-md opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none">
                                            El total de los clientes se calcula según los registros de todos los archivos ingresados desde el inicio del año fiscal seleccionado o la sumatoria total de todos los años registrados.
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3 bg-white rotate-45 arrow-tooltip"></div>
                                        </div>
                                    </span>
                                </div>
                            </h2>
                            <h1>{{ count($clientesImportes) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-4 px-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="w-6/10 flex-1">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="font-semibold text-l text-gray-800 leading-tight">Ingreso mensual</h1>
                </div>
                <div class="p-6">
                    <div class="p-6">
                        <div id="chart" class="w-full"></div>
                    </div>
                    <div id="chart" class="w-full"></div>
                </div>
            </div>
        </div>

        <div class="w-4/10" style="flex-basis: 40%;">
            <div class="bg-white shadow-sm sm:rounded-lg clientes-body">
                <div class="p-6 text-gray-900">
                    <h1 class="font-semibold text-l text-gray-800 leading-tight">Clientes</h1>
                </div>
                <div class="p-6 text-gray-900 clientes-list">
                    @if (count($clientesImportes))
                        <div class="overflow-y-auto max-h-[400px] border rounded-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Cliente
                                        </th>
                                        <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">
                                            Total (GTQ)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($clientesImportes as $cliente => $importe)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">{{ $cliente }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700 font-semibold">GTQ {{ number_format($importe, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Sin registro de clientes</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const options = {
            chart: {
                type: 'area',
                height: 400,
                zoom: { enabled: false },
            },
            stroke: {
                curve: 'smooth',
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: @json($meses),
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            series: @json($importes), // ← quitada la coma extra
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "GTQ " + new Intl.NumberFormat('es-GT', { minimumFractionDigits: 2 }).format(value);
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
</script>
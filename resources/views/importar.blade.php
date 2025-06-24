<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear nuevo reporte') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <p style="color: green;">{{ session('success') }}</p>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('movimientos.importar', ['evento' => $evento]) }}" method="POST" enctype="multipart/form-data" class="formReporte">
                        @csrf

                        <input type="hidden" name="evento_id" value="{{ $evento }}">

                        <h3 class="text-lg font-semibold">Ingresa tu archivo</h3>

                        <div class="uploadFile">
                            <label for="archivo_excel">Archivo Excel:</label>
                            <input type="file" name="archivo_excel" required>
                        </div>

                        <div class="fileName">
                            <label for="nombre_archivo">Nombre del archivo:</label>
                            <input type="text" name="nombre_archivo" id="nombre_archivo" required>
                        </div>

                        <h3>Proyección</h3>

                        @php
                            $meses = [
                                'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                                'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
                            ];
                        @endphp

                        <div class="monthContent grid grid-cols-2 gap-4">
                            @foreach($meses as $mes)
                                <div class="month">
                                    <label for="proyeccion_{{ $mes }}">{{ ucfirst($mes) }}:</label>
                                    <input type="number" name="proyeccion[{{ $mes }}]" step="0.01" required>
                                </div>
                            @endforeach
                        </div>

                        <br><br>
                        <button type="submit" class="btnPrimary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
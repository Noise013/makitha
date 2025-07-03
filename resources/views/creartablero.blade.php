<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear nuevo tablero') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('tableros.guardar') }}" method="POST" enctype="multipart/form-data" class="formReporte">
                        @csrf

                        {{-- Nombre del tablero --}}
                        <div class="fileName">
                            <label for="nombre_tablero">Nombre del tablero:</label>
                            <input type="text" id="nombre_tablero" name="nombre_tablero" required>
                        </div>
                        

                        {{-- Subir archivo Excel --}}
                        <div class="uploadFile">
                            <label for="archivo_consolidado">Archivo consolidado:</label>
                            <input type="file" id="archivo_consolidado" name="archivo_consolidado" required>
                        </div>

                        {{-- Seleccionar evento (reporte) --}}
                        <div class="uploadFile">
                            <label for="evento_id">Seleccionar reporte:</label>
                            <select name="evento_id" id="evento_id" required>
                                <option value="" selected disabled>-- Elegir un reporte --</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->nombre_archivo ?? 'Sin nombre' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btnPrimary">Cargar datos</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


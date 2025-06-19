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
                    <form action="{{ route('tableros.guardar') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Nombre del tablero --}}
                        <label for="nombre_tablero">Nombre del tablero:</label>
                        <input type="text" id="nombre_tablero" name="nombre_tablero" required>

                        {{-- Subir archivo Excel --}}
                        <label for="archivo_consolidado">Archivo consolidado:</label>
                        <input type="file" id="archivo_consolidado" name="archivo_consolidado" required>

                        {{-- Seleccionar evento (reporte) --}}
                       <label for="evento_id">Seleccionar reporte:</label>
                        <select name="evento_id" id="evento_id" required>
                            <option value="">-- Elegir un evento --</option>
                            @foreach ($eventos as $evento)
                                <option value="{{ $evento->id }}">{{ $evento->nombre_archivo ?? 'Sin nombre' }}</option>
                            @endforeach
                        </select>

                        <br><br>
                        <button type="submit">Cargar datos</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


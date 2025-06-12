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
                    <form action="{{ route('movimientos.importar', ['evento' => $evento]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="evento_id" value="{{ $evento }}">

                        <label for="nombre_archivo">Nombre del archivo:</label>
                        <input type="text" name="nombre_archivo" id="nombre_archivo" required>


                        <input type="file" name="archivo_excel" required>
                        <button type="submit">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
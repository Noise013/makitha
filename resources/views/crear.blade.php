
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Historial de reportes</h2>
                        
                        <form action="{{ route('importar.form', ['evento' => Str::uuid()]) }}" method="GET">
                            <button type="submit" class="btnPrimary">Crear nuevo reporte</button>
                        </form>
                    </div>
                    <ul class="space-y-2">
                        @forelse($eventos as $evento)
                           <li class="border p-4 rounded hover:bg-gray-50 listRep flex items-center justify-between">
                                <div>
                                    <a href="{{ url('/evento?id=' . $evento->id) }}" class="text-blue-600 hover:underline">
                                        {{ $evento->nombre_archivo ?? 'Sin nombre' }}
                                        <span class="fechaReporte">Creado el {{ $evento->created_at->format('d-m-Y') }}</span>
                                    </a>
                                </div>
                                <form action="{{ route('eventos.eliminar', $evento->id) }}" method="POST" onsubmit="return confirm('¿Seguro que querés eliminar este reporte?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btnDelete">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-500">No hay reportes aún.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#ECFDF5',
            color: '#065F46'
        });
    });
</script>
@endif
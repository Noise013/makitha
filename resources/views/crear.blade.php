
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <form action="{{ route('eventos.guardar') }}" method="POST">
                    @csrf
                    <button type="submit">Crear nuevo reporte</button>
                </form>
                <h2 class="text-lg font-semibold mb-4">Historial de eventos</h2>
                <ul class="space-y-2">
                    @forelse($eventos as $evento)
                        <li class="border p-4 rounded hover:bg-gray-50">
                            <a href="{{ url('/evento?id=' . $evento->id) }}" class="text-blue-600 hover:underline">
                                {{ $evento->nombre_archivo ?? 'Sin nombre' }} — {{ $evento->created_at->format('Y-m-d H:i') }}
                            </a>
                        </li>
                    @empty
                        <li class="text-gray-500">No hay eventos aún.</li>
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
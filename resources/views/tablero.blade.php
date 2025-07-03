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
                        @forelse($tableros as $tablero)
                            <li class="border p-4 rounded hover:bg-gray-50 listRep flex items-center justify-between">
                                <div>
                                    <a href="{{ route('tableros.detalle', ['id' => $tablero->id]) }}" class="text-blue-600 hover:underline">
                                        {{ $tablero->nombre_tablero ?? 'Sin nombre' }}
                                        <span class="fechaReporte">Creado el {{ $tablero->created_at->format('d-m-Y') }}</span>
                                    </a>
                                </div>
                               <form action="{{ route('tableros.eliminar', $tablero->id) }}" method="POST" class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btnDelete">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="text-gray-500">No hay tableros aún.</li>
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

       
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
</script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.form-eliminar').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

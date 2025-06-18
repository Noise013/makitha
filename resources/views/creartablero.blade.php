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
                    <form action="{{ route('tableros.guardar') }}" method="POST">
                        @csrf
                        {{-- Acá van los campos más adelante --}}
                        <p>Acá va el formulario.</p>

                        <br>
                        <button type="submit">Guardar tablero</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

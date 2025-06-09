<!DOCTYPE html>
<html>
<head>
    <title>Importar Excel</title>
</head>
<body>
    <h1>Subir archivo Excel</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('movimientos.importar', ['evento' => $evento]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="evento_id" value="{{ $evento }}">
        <input type="file" name="archivo_excel" required>
        <button type="submit">Importar</button>
    </form>

</body>
</html>

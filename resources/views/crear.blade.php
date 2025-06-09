<!DOCTYPE html>
<html>
<head>
    <title>Crear nuevo evento</title>
</head>
<body>
    <h1>Crear nuevo evento</h1>

    <form action="{{ route('eventos.guardar') }}" method="POST">
        @csrf
        <button type="submit">Crear nuevo evento</button>
    </form>
</body>
</html>

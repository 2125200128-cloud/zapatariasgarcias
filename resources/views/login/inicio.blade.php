<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    @vite('resources/js/app.js')
</head>
<body>
  <div class="card">
    <!-- Formulario de login (izquierda) -->
    <form class="form" action="{{ route('login') }}" method="POST">
      @csrf
      <h2>Iniciar Sesión</h2>
      @if ($errors->any())
        <div class="error-box">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="field">
        <input type="text" name="usuario" placeholder="Usuario" value="{{ old('usuario') }}" required autofocus />
      </div>
      <div class="field">
        <input type="password" name="password" placeholder="Contraseña" required />
      </div>
      <button class="submit" type="submit">ENTRAR</button>
    </form>

    <!-- Panel azul fijo (derecha) -->
    <div class="hero">
      <h2>¡Bienvenido de nuevo!</h2>
      <p>Accede al panel administrativo y gestiona tus proyectos fácilmente.</p>
    </div>
  </div>
</body>
</html>

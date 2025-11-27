@component('mail::message')
# Hola {{ $user->name }} 👋

@if ($isAutoCreated)
Tu cuenta ha sido creada automáticamente durante tu proceso de compra.

@if ($password)
## Tu contraseña temporal es:
**{{ $password }}**

Por favor inicia sesión y cámbiala cuanto antes.
@endif

@else
Gracias por registrarte en nuestra plataforma.
@endif

@component('mail::button', ['url' => url('/login')])
Iniciar sesión
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent

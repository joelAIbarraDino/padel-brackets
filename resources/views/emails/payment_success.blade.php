@component('mail::message')
# @if($type === 'tournament') ¡Tu lugar ha sido reservado! @else ¡Tu membresía está activa! @endif

Hola **{{ $user->name }}**,  
Gracias por tu compra.

@if($type === 'tournament')
Has reservado tu lugar en el torneo **#{{ $extraData->id }}**.  
Tu lugar ha sido marcado como **ocupado**.
@else
Has adquirido la membresía **{{ $extraData->name ?? 'sin nombre' }}**.  
Válida hasta el **{{ \Carbon\Carbon::parse($extraData->expired_at)->format('d/m/Y H:i') }}**.
@endif

**Detalles del pago:**
- Monto: ${{ number_format($payment->amount, 2) }} {{ strtoupper($payment->currency) }}
- Método: {{ strtoupper($payment->method) }}
- Estado: {{ ucfirst($payment->status) }}
- ID de transacción: {{ $payment->id_transaction }}

---

**Correo asociado:** {{ $user->email }}

@component('mail::button', ['url' => config('app.url'), 'color' => 'success'])
Ir al sitio
@endcomponent

Gracias por confiar en nosotros,<br>
**{{ config('app.name') }}**
@endcomponent

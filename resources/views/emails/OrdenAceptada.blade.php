@component('mail::message')
# La Orden  ha sido Aceptada por el cliente

Para ver los detalles de la orden, pulsa el siguiente boton.

@component('mail::button', ['url' => url('login')])
Ir a T&G
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent

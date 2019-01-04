@component('mail::message')
{{ config('app.name') }}
# Hemos registrado un cambio en tu orden #

Utiliza estas credenciales para acceder al sistema.
{{--
@component('mail::table')
	| Usuario | Contraseña |
	|:---------|:------------|
	| {{ $user }} | {{ $pass }} |

@endcomponent
--}}
@component('mail::button', ['url' => 'login'])
Ingresar
@endcomponent

Gracias por usar,
{{ config('app.name') }}.
@endcomponent

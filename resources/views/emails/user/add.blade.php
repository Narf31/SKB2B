
@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => url('/')])
            Вы зарегистрированы в системе М5 2.0
        @endcomponent
    @endslot
{{-- Body --}}

Для входа в систему используйте приведенные ниже данные<br>
логин: {{$email}}<br>
пароль: {{$password}}<br>

@component('mail::button', ['url' => url('/')])
Вход
@endcomponent

{{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
{{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}.
        @endcomponent
    @endslot
@endcomponent




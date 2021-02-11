<article class="auth__image" style="background-image: url({{ asset('images/login-fondo-1.png') }})">
    @if($company->logo)
        <img class="auth__logo" src="{{ asset('storage/uploads/logos/' . $company->logo) }}" alt="Logo" />
    @else
        <img class="auth__logo" src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
    @endif
</article>

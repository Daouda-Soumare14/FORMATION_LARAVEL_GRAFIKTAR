@extends('base')

@section('content')
    <h1>Se connecter</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('auth.login') }}" method="post" class="vstack gap-3">

                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" id="email" value="{{ old('eamil') }}">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input class="form-control" type="password" name="password" id="password">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>

                <button class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
@endsection
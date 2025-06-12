@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-end">
        <div class="col-md-6">
            <div class="card" style="background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(10px); margin-top: 100px; color: white;">
                <div class="card-header" style="background: rgba(0, 0, 0, 0.7); color: white;">Register</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right" style="color: white;">Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="background: rgba(0, 0, 0, 0.3); color: white; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 20px; box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right" style="color: white;">Email Address</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="background: rgba(0, 0, 0, 0.3); color: white; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 20px; box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right" style="color: white;">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" style="background: rgba(0, 0, 0, 0.3); color: white; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 20px; box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right" style="color: white;">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" style="background: rgba(0, 0, 0, 0.3); color: white; border: 2px solid rgba(255, 255, 255, 0.3); border-radius: 20px; box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3);">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-image: url('{{ asset('indir.jpeg') }}');
        background-size: cover;
        background-position: center;
        backdrop-filter: blur(10px);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }
    .form-control {
        background-image: url('{{ asset('logo.png') }}');
        background-repeat: no-repeat;
        background-position: 10px center;
        padding-left: 40px;
    }
</style>
@endsection 
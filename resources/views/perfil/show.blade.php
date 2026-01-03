@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container px-2 px-sm-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0"><i class="fas fa-user me-2"></i>Mi Perfil</h1>
        <a href="{{ route('tesis.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left me-1"></i><span class="d-none d-sm-inline">Volver</span>
        </a>
    </div>

    <div class="row g-2 g-md-3">
        {{-- Datos actuales --}}
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header px-2 px-sm-3"><h5 class="mb-0">Datos personales</h5></div>
                <div class="card-body px-2 px-sm-3">
                    <table class="table table-sm mb-0">
                        <tr><th>Nombre</th><td class="text-break">{{ auth()->user()->name }}</td></tr>
                        <tr><th>Email</th><td class="text-break">{{ auth()->user()->email }}</td></tr>
                        <tr><th>Rol</th><td><span class="badge bg-secondary">{{ ucfirst(auth()->user()->role) }}</span></td></tr>
                        <tr><th>Email verificado</th>
                            <td>
                                @if(auth()->user()->email_verified_at)
                                    <i class="fas fa-check-circle text-success"></i>
                                @else
                                    <i class="fas fa-times-circle text-danger"></i>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

       {{-- Cambiar / verificar email --}}
<div class="col-12 col-md-6">
    <div class="card h-100">
        <div class="card-header px-2 px-sm-3">
            <h5 class="mb-0">Email</h5>
        </div>
        <div class="card-body px-2 px-sm-3">
            {{-- Email actual --}}
            <p class="mb-1 small text-muted">Actual: <strong>{{ auth()->user()->email }}</strong></p>
            <p class="mb-2 small">
                @if(auth()->user()->email_verified_at)
                    <i class="fas fa-check-circle text-success"></i> Verificado
                @else
                    <i class="fas fa-times-circle text-danger"></i> No verificado
                @endif
            </p>

            {{-- Paso 1: Botón para enviar token --}}
            <form method="POST" action="{{ route('perfil.email') }}" class="mb-2">
                @csrf
                <div class="mb-2">
                    <label class="form-label small">Nuevo email</label>
                    <input type="email" name="email" class="form-control form-control-sm" required>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100">Enviar código</button>
            </form>

            {{-- Paso 2: Formulario para verificar (solo si NO verificado) --}}
@if(!auth()->user()->email_verified_at)
    <form method="POST" action="{{ route('perfil.verify.manual') }}">
        @csrf
        <div class="mb-2">
            <label class="form-label small">Código recibido</label>
            {{-- Aquí pegas el código --}}
            <input type="text" name="token" class="form-control form-control-sm" required>
        </div>
        <button type="submit" class="btn btn-success btn-sm w-100">Verificar email</button>
    </form>
@endif

        {{-- Cambiar contraseña --}}
        <div class="col-12 col-md-6">
            <div class="card h-100">
                <div class="card-header px-2 px-sm-3"><h5 class="mb-0">Cambiar contraseña</h5></div>
                <div class="card-body px-2 px-sm-3">
                    <form method="POST" action="{{ route('perfil.password.token') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm w-100">Enviar token a mi email</button>
                    </form>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('perfil.password') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small">Token recibido</label>
                            <input type="text" name="token" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control form-control-sm" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-sm" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">Actualizar contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
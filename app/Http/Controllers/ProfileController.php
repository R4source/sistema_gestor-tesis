<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    // Ver perfil
    public function show()
    {
        return view('perfil.show');
    }

    // Actualizar email (envía token)
    public function updateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:users,email,' . auth()->id()]);
        $token = Str::random(60);
        auth()->user()->update([
            'email' => $request->email,
            'email_verified_at' => null,
            'email_token' => $token,
        ]);

        // Mensaje simple sin enlace roto
        Mail::raw(
            "Para verificar tu nuevo email responde a este mensaje con el código: {$token}",
            fn($message) => $message->to($request->email)->subject('Verifica tu nuevo email')
        );

        return back()->with('success', 'Te hemos enviado un código de verificación a tu nuevo email.');
    }

   // Verificar email con código (manual)
    public function verifyManual(Request $request)
    {
    $request->validate(['token' => 'required|string']);
    $user = auth()->user();

    if ($user->email_token === $request->token) {
        $user->update(['email_verified_at' => now(), 'email_token' => null]);
        return back()->with('success', 'Email verificado correctamente.');
    }

    return back()->withErrors(['token' => 'Código inválido.']);
    }

    // Enviar token para cambiar contraseña
    public function sendPasswordToken(Request $request)
    {
        $user = auth()->user();
        $token = Password::createToken($user);

        Mail::raw(
            "Usa este token para cambiar tu contraseña:\n{$token}",
            fn($message) => $message->to($user->email)->subject('Token para cambiar contraseña')
        );

        return back()->with('success', 'Te hemos enviado un token a tu email.');
    }

    // Actualizar contraseña con token
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Password::tokenExists($user, $request->token)) {
            return back()->withErrors(['token' => 'Token inválido o expirado.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        Password::deleteToken($user);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
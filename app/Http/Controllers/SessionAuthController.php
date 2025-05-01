<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailMailable;


class SessionAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => [
                'required',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'unique:users,email'
            ],
            'password'  => 'required|confirmed|min:8',
        ], [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        $verificationUrl = url('/verify-email/' . $user->id . '/' . sha1($user->email));

        Mail::to($user->email)->send(new VerifyEmailMailable($user, $verificationUrl));

        // $user->notify(new EmailVerificationNotification($user));

        return redirect('/')->with('info', 'Te enviamos un correo para verificar tu cuenta.');
    }

    public function verifyEmail($id, $hash){
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash){
            abort(403, 'Enlace invalido.');
        }

        if ($user->email_verified_at){
            return redirect('/')->with('info', 'Tu correo ya fue verificado.');
        }

        $user->email_verified_at = now();
        $user->save();

        return redirect('/')->with('success', 'Correo verificado. Ya podes iniciar sesión.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        // if the user does not exist
        if (!$user){
            return redirect('/')->with('error', 'Este usuario no esta registrado.');
        }

        // incorrect password 
        if (!Hash::check($credentials['password'], $user->password)){
            return redirect('/login')->with('error', 'Las credenciales no coinciden.');
        }

        Auth::login($user);

        if (is_null($user->email_verified_at)){
            $verificationUrl = url('/verify-email/' . $user->id . '/' . sha1($user->email));
            Mail::to($user->email)->send(new VerifyEmailMailable($user, $verificationUrl));

            Auth::logout();
            return redirect('/')->with([
                'email_verified' => false,
                'status' => 'Se volvió a enviar un correo para su verificación.'
            ]);
        }

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('logout', 'Sesión cerrada.');
    }
}

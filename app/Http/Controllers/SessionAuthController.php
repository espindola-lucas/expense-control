<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Actions\Auth\SendPasswordResetLinkAction;
use App\Actions\Auth\VerifyEmailAction;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class SessionAuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $action)
    {
        $action->execute($request->validated());

        return redirect('/')->with('info', 'Te enviamos un correo para verificar tu cuenta.');
    }

    public function verifyEmail(VerifyEmailAction $action, $id, $hash)
    {
        $user   = User::findOrFail($id);
        $status = $action->execute($user, $hash);

        return $status === 'already_verified'
            ? redirect('/')->with('info', 'Tu correo ya fue verificado.')
            : redirect('/')->with('success', 'Correo verificado. Ya podes iniciar sesión.');
    }

    public function login(LoginRequest $request, LoginUserAction $action)
    {
        $result = $action->execute($request->email, $request->password);

        return match ($result['status']) {
            'invalid_credentials' => response()->json(['message' => 'Invalid credentials.'], 401),
            'email_not_verified'  => response()->json(['message' => 'Email not verified. A new verification email has been sent.'], 403),
            default               => response()->json([
                'token' => $result['token'],
                'user'  => new UserResource($result['user']),
            ]),
        };
    }

    public function logout(Request $request, LogoutUserAction $action)
    {
        $action->execute($request);

        return response()->json(['message' => 'Logged out.']);
    }

    public function showForgotPasswordForm()
    {
        return view('session.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request, SendPasswordResetLinkAction $action)
    {
        $sent = $action->execute($request->email);

        return $sent
            ? redirect('/')->with('info', 'Te enviamos un correo con el enlace para restablecer tu contraseña.')
            : back()->withErrors(['email' => 'No se encontró un usuario con ese correo.']);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('session.new-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(ResetPasswordRequest $request, ResetPasswordAction $action)
    {
        $status = $action->execute($request->only('email', 'password', 'password_confirmation', 'token'));

        return $status === Password::PASSWORD_RESET
            ? redirect('/')->with('info', 'Contraseña restablecida correctamente.')
            : back()->withErrors(['email' => __($status)]);
    }
}

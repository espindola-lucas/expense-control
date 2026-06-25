<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\ResetPasswordMailable;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLinkAction
{
    public function execute(string $email): bool
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        $token    = Password::createToken($user);
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        Mail::to($user->email)->send(new ResetPasswordMailable($user, $resetUrl));

        return true;
    }
}

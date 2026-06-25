<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\VerifyEmailMailable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginUserAction
{
    public function execute(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return ['status' => 'invalid_credentials'];
        }

        if (is_null($user->email_verified_at)) {
            $verificationUrl = url('/verify-email/' . $user->id . '/' . sha1($user->email));
            Mail::to($user->email)->send(new VerifyEmailMailable($user, $verificationUrl));

            return ['status' => 'email_not_verified'];
        }

        return [
            'status' => 'success',
            'user'   => $user,
            'token'  => $user->createToken('api-token')->plainTextToken,
        ];
    }
}

<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\VerifyEmailMailable;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $verificationUrl = url('/verify-email/' . $user->id . '/' . sha1($user->email));
        Mail::to($user->email)->send(new VerifyEmailMailable($user, $verificationUrl));

        return $user;
    }
}

<?php
declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;

class VerifyEmailAction
{
    public function execute(User $user, string $hash): string
    {
        if (sha1($user->email) !== $hash) {
            abort(403, 'Enlace invalido.');
        }

        if ($user->email_verified_at) {
            return 'already_verified';
        }

        $user->email_verified_at = now();
        $user->save();

        return 'verified';
    }
}

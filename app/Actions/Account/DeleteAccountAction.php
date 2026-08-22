<?php
declare(strict_types=1);

namespace App\Actions\Account;

use App\Models\Account;

class DeleteAccountAction
{
    public function execute(Account $account): void
    {
        if ($account->movements()->exists() || $account->incomingTransfers()->exists()) {
            abort(422, 'No se puede eliminar una cuenta con movimientos. Archivala en su lugar.');
        }

        $account->delete();
    }
}

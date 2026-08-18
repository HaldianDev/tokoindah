<?php

namespace App\Policies;

use App\Models\Installment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InstallmentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isOwner() || $user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Installment $installment): bool
    {
        return $user->id === $installment->order->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Installment $installment): bool
    {
        return $user->isOwner() || $user->isAdmin();
    }
}

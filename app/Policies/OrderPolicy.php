<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
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
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->isOwner() || $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->isOwner() || $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can upload payment proof.
     */
    public function uploadCashProof(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->payment_method === 'cash';
    }
}

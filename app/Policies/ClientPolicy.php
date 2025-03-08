<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'client']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Client $model): bool
    {
        return $user->hasRole(['admin'])
            || $user->hasRole('client') && $user->client_id == $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $model): bool
    {
        return $user->hasRole(['admin']) || $user->client_id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Client $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Client $model): bool
    {
        return false;
    }
}

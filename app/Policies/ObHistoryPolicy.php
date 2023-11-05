<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ObHistory;
use App\Models\User;

class ObHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any ObHistory');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ObHistory $obhistory): bool
    {
        return $user->can('view ObHistory');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ObHistory');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ObHistory $obhistory): bool
    {
        return $user->can('update ObHistory');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ObHistory $obhistory): bool
    {
        return $user->can('delete ObHistory');
    }


}

<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Individual;
use App\Models\User;

class IndividualPolicy
{



    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Individual');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Individual $individual): bool
    {
        return $user->can('view Individual');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Individual');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Individual $individual): bool
    {
        return $user->can('update Individual');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Individual $individual): bool
    {
        return $user->can('delete Individual');
    }


}

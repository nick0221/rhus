<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FollowupCheckup;
use App\Models\User;

class FollowupCheckupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any FollowupCheckup');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FollowupCheckup $followupcheckup): bool
    {
        return $user->can('view FollowupCheckup');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create FollowupCheckup');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FollowupCheckup $followupcheckup): bool
    {
        return $user->can('update FollowupCheckup');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FollowupCheckup $followupcheckup): bool
    {
        return $user->can('delete FollowupCheckup');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FollowupCheckup $followupcheckup): bool
    {
        return $user->can('{{ restorePermission }}');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FollowupCheckup $followupcheckup): bool
    {
        return $user->can('{{ forceDeletePermission }}');
    }
}

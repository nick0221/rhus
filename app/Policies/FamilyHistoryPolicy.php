<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FamilyHistory;
use App\Models\User;

class FamilyHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any FamilyHistory');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FamilyHistory $familyhistory): bool
    {
        return $user->can('view FamilyHistory');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create FamilyHistory');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FamilyHistory $familyhistory): bool
    {
        return $user->can('update FamilyHistory');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FamilyHistory $familyhistory): bool
    {
        return $user->can('delete FamilyHistory');
    }


}

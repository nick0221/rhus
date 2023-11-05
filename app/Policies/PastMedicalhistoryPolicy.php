<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PastMedicalhistory;
use App\Models\User;

class PastMedicalhistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any PastMedicalhistory');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PastMedicalhistory $pastmedicalhistory): bool
    {
        return $user->can('view PastMedicalhistory');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create PastMedicalhistory');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PastMedicalhistory $pastmedicalhistory): bool
    {
        return $user->can('update PastMedicalhistory');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PastMedicalhistory $pastmedicalhistory): bool
    {
        return $user->can('delete PastMedicalhistory');
    }


}

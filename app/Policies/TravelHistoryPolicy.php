<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TravelHistory;
use App\Models\User;

class TravelHistoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any TravelHistory');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TravelHistory $travelhistory): bool
    {
        return $user->can('view TravelHistory');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create TravelHistory');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TravelHistory $travelhistory): bool
    {
        return $user->can('update TravelHistory');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TravelHistory $travelhistory): bool
    {
        return $user->can('delete TravelHistory');
    }


}

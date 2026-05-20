<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workshop;

class WorkshopPolicy
{
    /*
    |--------------------------------------------------------------------------
    | VIEW ANY
    |--------------------------------------------------------------------------
    */

    public function viewAny(
        User $user
    ): bool {

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    public function view(
        User $user,
        Workshop $workshop
    ): bool {

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user
    ): bool {

        return $user->can('workshop:create');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        User $user,
        Workshop $workshop
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | ADMIN CAN UPDATE ANYTHING
        |--------------------------------------------------------------------------
        */

        if ($user->can('workshop:update:any')) {
            return true;
        }

        return
            $user->can('workshop:update:own')
            &&
            $workshop->trainer_id === $user->id;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        User $user,
        Workshop $workshop
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | ADMIN CAN DELETE ANYTHING
        |--------------------------------------------------------------------------
        */
        if ($user->can('workshop:delete:any')) {
                return true;
            }

        return
            $user->can('workshop:delete:own')
            &&
            $workshop->trainer_id === $user->id;
    }
}
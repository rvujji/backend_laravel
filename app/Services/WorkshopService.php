<?php

namespace App\Services;

use App\Models\Workshop;
use App\Models\User;

class WorkshopService
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function list()
    {
        return Workshop::query()

            ->with([
                'category',
                'owner'
            ])

            ->latest()

            ->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        User $owner,
        array $data
    ): Workshop {

        $data['owner_id'] = $owner->id;

        return Workshop::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Workshop $workshop,
        array $data
    ): Workshop {

        $workshop->update($data);

        return $workshop->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        Workshop $workshop
    ): void {

        $workshop->delete();
    }
}
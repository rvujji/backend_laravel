<?php

namespace App\Services;

use App\Models\WorkshopOffering;
use Illuminate\Support\Str;

class WorkshopOfferingService
{
    public function create(array $data): WorkshopOffering
    {
        $data['slug'] = Str::slug($data['title']) .
            '-' . uniqid();

        return WorkshopOffering::create($data);
    }

    public function update(
        WorkshopOffering $offering,
        array $data
    ): WorkshopOffering {

        $offering->update($data);


        $fresh = WorkshopOffering::find(
            $offering->id
        );


        return $fresh ?? $offering;
    }

    public function delete(
        WorkshopOffering $offering
    ): bool {

        return $offering->delete();
    }
}

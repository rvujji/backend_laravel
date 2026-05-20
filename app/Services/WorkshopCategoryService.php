<?php

namespace App\Services;

use App\Models\WorkshopCategory;

class WorkshopCategoryService
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function list()
    {
        return WorkshopCategory::query()
            ->latest()
            ->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(array $data): WorkshopCategory
    {
        return WorkshopCategory::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        WorkshopCategory $category,
        array $data
    ): WorkshopCategory {

        $category->update($data);

        return $category->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        WorkshopCategory $category
    ): void {

        $category->delete();
    }
}
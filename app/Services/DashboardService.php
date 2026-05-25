<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopOffering;
use App\Models\WorkshopOfferingEnrollment;

class DashboardService
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD STATS
    |--------------------------------------------------------------------------
    */

    public function stats(): array
    {
        return [

            'total_students' =>
            User::role('student')->count(),

            'total_workshops' =>
            Workshop::count(),

            'published_workshops' =>
            Workshop::where(
                'status',
                'published'
            )->count(),

            'total_offerings' =>
            WorkshopOffering::count(),

            'total_enrollments' =>
            WorkshopOfferingEnrollment::count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    public function recentEnrollments()
    {
        return WorkshopOfferingEnrollment::query()

            ->with([
                'student:id,name',
                'offering:id,workshop_id,title',
                'offering.workshop:id,title',
            ])

            ->latest()

            ->limit(10)

            ->get()

            ->map(function ($enrollment) {

                return [

                    'id' =>
                    $enrollment->id,

                    'student_id' =>
                    $enrollment->student_id,

                    'student_name' =>

                    trim(
                        ($enrollment->student->name ?? '')
                    ),

                    /*
                    |--------------------------------------------------------------------------
                    | Offering
                    |--------------------------------------------------------------------------
                    */

                    'offering_id' =>
                    $enrollment->offering?->id,

                    'offering_title' =>
                    $enrollment->offering?->title,

                    /*
                    |--------------------------------------------------------------------------
                    | Workshop
                    |--------------------------------------------------------------------------
                    */

                    'workshop_id' =>
                    $enrollment->offering?->workshop?->id,

                    'workshop_title' =>
                    $enrollment
                        ->offering
                        ?->workshop
                        ?->title,

                    /*
                    |--------------------------------------------------------------------------
                    | Enrollment
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                    $enrollment->status,

                    'completion_status' =>
                    $enrollment->completion_status,

                    'created_at' =>
                    $enrollment->created_at,
                ];
            });
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT WORKSHOPS
    |--------------------------------------------------------------------------
    */

    public function recentWorkshops()
    {
        return Workshop::query()

            ->with([
                'owner:id,name'
            ])

            ->latest()

            ->limit(10)

            ->get()

            ->map(function ($workshop) {

                return [

                    'id' =>
                    $workshop->id,

                    'title' =>
                    $workshop->title,

                    'slug' =>
                    $workshop->slug,

                    'status' =>
                    $workshop->status,

                    'owner_id' =>
                    $workshop->owner_id,

                    'owner_name' =>

                    trim(
                        ($workshop->owner->name ?? '')
                    ),

                    'created_at' =>
                    $workshop->created_at,
                ];
            });
    }
}

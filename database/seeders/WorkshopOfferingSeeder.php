<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workshop;
use App\Models\WorkshopOffering;

class WorkshopOfferingSeeder extends Seeder
{
    public function run(): void
    {
        $ros2Workshop =
            Workshop::where(
                'slug',
                'ros2-robotics-bootcamp'
            )->first();

        WorkshopOffering::create([

            'workshop_id' =>
            $ros2Workshop?->id,

            'title' =>
            'ROS2 June 2026 Batch',

            'slug' =>
            'ros2-june-2026-batch',

            'delivery_mode' =>
            'hybrid',

            'enrollment_type' =>
            'session_selection',

            'session_selection_rule' =>
            'any_n_of_m',

            'completion_rule' =>
            'attend_n_sessions',

            'capacity_mode' =>
            'both',

            'minimum_sessions_required' =>
            4,

            'maximum_sessions_selectable' =>
            6,

            'status' =>
            'published',
        ]);

        WorkshopOffering::create([

            'workshop_id' =>
            $ros2Workshop?->id,

            'title' =>
            'ROS2 Weekend Cohort',

            'slug' =>
            'ros2-weekend-cohort',

            'delivery_mode' =>
            'virtual',

            'enrollment_type' =>
            'full_series',

            'session_selection_rule' =>
            'all_sessions',

            'completion_rule' =>
            'attend_all_required',

            'capacity_mode' =>
            'offering_only',

            'minimum_sessions_required' =>
            6,

            'maximum_sessions_selectable' =>
            6,

            'status' =>
            'published',
        ]);
    }
}

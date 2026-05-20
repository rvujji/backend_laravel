<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\WorkshopCategory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WorkshopCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('workshops')->truncate();
        DB::table('workshop_categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [

            [
                'name' => 'Robotics',
                'description' => 'Robotics workshops and training'
            ],

            [
                'name' => 'Artificial Intelligence',
                'description' => 'AI and machine learning programs'
            ],

            [
                'name' => 'Electronics',
                'description' => 'Electronics fundamentals and projects'
            ],

            [
                'name' => 'Embedded Systems',
                'description' => 'Embedded systems engineering'
            ],

            [
                'name' => 'IoT',
                'description' => 'Internet of Things development'
            ],

            [
                'name' => 'Programming',
                'description' => 'Software development and programming'
            ],

            [
                'name' => 'Computer Vision',
                'description' => 'Vision systems and image processing'
            ],

            [
                'name' => 'ROS2',
                'description' => 'ROS2 robotics middleware training'
            ],

            [
                'name' => 'SLAM',
                'description' => 'Simultaneous Localization and Mapping'
            ],

            [
                'name' => 'Autonomous Systems',
                'description' => 'Autonomous robotics and navigation'
            ],

            [
                'name' => 'Drone Technology',
                'description' => 'Drone systems and UAV engineering'
            ],

            [
                'name' => '3D Printing',
                'description' => 'Additive manufacturing technologies'
            ],

            [
                'name' => 'Mechanical Design',
                'description' => 'Mechanical engineering and CAD'
            ],

            [
                'name' => 'Cyber Security',
                'description' => 'Cyber security fundamentals'
            ],

            [
                'name' => 'Cloud Computing',
                'description' => 'Cloud infrastructure and deployment'
            ],

            [
                'name' => 'Data Science',
                'description' => 'Data analysis and data engineering'
            ],
        ];

        foreach ($categories as $category) {

            WorkshopCategory::updateOrCreate(

                [
                    'slug' => Str::slug($category['name'])
                ],

                [
                    'name' => $category['name'],

                    'slug' => Str::slug($category['name']),

                    'description' => $category['description'],

                    'is_active' => true,
                ]
            );
        }
    }
}
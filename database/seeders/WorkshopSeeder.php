<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use App\Models\WorkshopCategory;

class WorkshopSeeder extends Seeder
{
    public function run(): void
    {
        $roboticsCategory =
            WorkshopCategory::where(
                'slug',
                'robotics'
            )->first();

        $aiCategory =
            WorkshopCategory::where(
                'slug',
                'ai'
            )->first();

        $adminId =
            User::role('admin')->value('id');

        /*
        |--------------------------------------------------------------------------
        | ROS2 Robotics Bootcamp
        |--------------------------------------------------------------------------
        */

        Workshop::create([

            'category_id' =>
            $roboticsCategory?->id,

            'owner_id' =>
            $adminId,

            'title' =>
            'ROS2 Robotics Bootcamp',

            'slug' =>
            'ros2-robotics-bootcamp',

            'short_description' =>
            'Comprehensive ROS2 robotics engineering bootcamp.',

            'full_description' =>
            'Learn ROS2, Gazebo, Navigation2, SLAM, robot architecture, deployment, and autonomous robotics engineering workflows.',

            'thumbnail' =>
            'workshops/ros2-thumbnail.jpg',

            'video_url' =>
            'https://youtube.com/watch?v=ros2-demo',

            'delivery_mode_default' =>
            'hybrid',

            'difficulty_level' =>
            'intermediate',

            'language' =>
            'English',

            'tags' =>
            json_encode([
                'ROS2',
                'SLAM',
                'Navigation2',
                'Gazebo',
                'Robotics',
            ]),

            'learning_outcomes' =>
            'Build robotics systems using ROS2 and simulation environments.',

            'requirements' =>
            'Basic Linux and programming knowledge.',

            'duration_minutes' =>
            2400,

            'timezone' =>
            'Asia/Kolkata',

            'price' =>
            14999,

            'is_featured' =>
            true,

            'status' =>
            'published',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Autonomous Drone Systems
        |--------------------------------------------------------------------------
        */

        Workshop::create([

            'category_id' =>
            $roboticsCategory?->id,

            'owner_id' =>
            $adminId,

            'title' =>
            'Autonomous Drone Systems',

            'slug' =>
            'autonomous-drone-systems',

            'short_description' =>
            'Drone autonomy and mission planning systems.',

            'full_description' =>
            'Hands-on workshop covering PX4, MAVROS, autonomous navigation, obstacle avoidance, and drone mission systems.',

            'thumbnail' =>
            'workshops/drone-thumbnail.jpg',

            'video_url' =>
            'https://youtube.com/watch?v=drone-demo',

            'delivery_mode_default' =>
            'physical',

            'difficulty_level' =>
            'advanced',

            'language' =>
            'English',

            'tags' =>
            json_encode([
                'Drones',
                'PX4',
                'MAVROS',
                'Autonomy',
            ]),

            'learning_outcomes' =>
            'Build autonomous drone systems with PX4 and ROS.',

            'requirements' =>
            'Python and robotics basics.',

            'duration_minutes' =>
            3000,

            'timezone' =>
            'Asia/Kolkata',

            'price' =>
            19999,

            'is_featured' =>
            true,

            'status' =>
            'published',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AI for Autonomous Machines
        |--------------------------------------------------------------------------
        */

        Workshop::create([

            'category_id' =>
            $aiCategory?->id,

            'owner_id' =>
            $adminId,

            'title' =>
            'AI for Autonomous Machines',

            'slug' =>
            'ai-for-autonomous-machines',

            'short_description' =>
            'Applied AI systems for robotics.',

            'full_description' =>
            'Computer vision, perception pipelines, planning systems, and AI architectures for autonomous machines.',

            'thumbnail' =>
            'workshops/ai-thumbnail.jpg',

            'video_url' =>
            'https://youtube.com/watch?v=ai-demo',

            'delivery_mode_default' =>
            'virtual',

            'difficulty_level' =>
            'advanced',

            'language' =>
            'English',

            'tags' =>
            json_encode([
                'AI',
                'Computer Vision',
                'Perception',
                'Autonomy',
            ]),

            'learning_outcomes' =>
            'Design AI perception systems for robotics applications.',

            'requirements' =>
            'Python and machine learning basics.',

            'duration_minutes' =>
            3600,

            'timezone' =>
            'Asia/Kolkata',

            'price' =>
            24999,

            'is_featured' =>
            true,

            'status' =>
            'published',
        ]);
    }
}

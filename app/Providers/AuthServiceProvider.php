<?php

namespace App\Providers;

use App\Models\Workshop;
use App\Policies\WorkshopPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Workshop::class => WorkshopPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
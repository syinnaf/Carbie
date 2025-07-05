<?php

namespace App\Providers;

use App\Models\UserTodo;
use App\Policies\UserTodoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserTodo::class => UserTodoPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}

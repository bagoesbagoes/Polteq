<?php

namespace App\Providers;

use App\Models\Proposal;
use App\Policies\ProposalPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(Proposal::class, ProposalPolicy::class);
    }
}
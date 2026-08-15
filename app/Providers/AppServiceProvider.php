<?php

namespace App\Providers;

use App\Models\AgmtDraft;
use App\Models\Agreement;
use App\Models\AgreementName;
use App\Observers\AgmtDraftObserver;
use App\Observers\AgreementNameObserver;
use App\Observers\AgreementObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production') || app()->environment('development')) {
            URL::forceScheme('https');
        }

        Agreement::observe(AgreementObserver::class);
        AgmtDraft::observe(AgmtDraftObserver::class);
        AgreementName::observe(AgreementNameObserver::class);
    }
}

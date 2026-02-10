<?php

namespace Kamel\Auditable\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Kamel\Auditable\QueryBuilders\AuditableQueryBuilder;

class AuditableServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the service provider.
     *
     * @return void
     */
    public function boot()
    {
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('auditable.query_builder', AuditableQueryBuilder::class);
    }

}

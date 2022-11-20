<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        JsonResource::withoutWrapping();
        Model::preventLazyLoading(!$this->app->isProduction());
        Relation::enforceMorphMap([
            'composition' => 'App\Models\Composition',
        ]);
    }
}

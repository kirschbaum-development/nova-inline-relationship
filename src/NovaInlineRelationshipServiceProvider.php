<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Illuminate\Support\ServiceProvider;
use KirschbaumDevelopment\NovaInlineRelationship\Helpers\NovaInlineRelationshipHelper;
use KirschbaumDevelopment\NovaInlineRelationship\Exceptions\UnsupportedRelationshipType;

class NovaInlineRelationshipServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('nova-inline-relationships.php'),
            ], 'nova-inline-relationships');
        }

        Nova::serving(function () {
            Nova::script('nova-inline-relationship', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-inline-relationship', __DIR__ . '/../dist/css/field.css');
        });

        Field::macro('inline', function (Request $Request) {
            if (! class_exists(NovaInlineRelationshipHelper::getObserver($this))) {
                throw UnsupportedRelationshipType::create(class_basename($this), $this->attribute);
            }

            // simple switch to test between original and modified
            $InlineRelationship = config('nova-inline-relationships.custom', false) === false ?
                NovaInlineRelationship::class :
                config('nova-inline-relationships.custom');

            return $InlineRelationship::make($this->name, $this->attribute)
                ->resourceClass($this->resourceClass)
                ->setRequest($Request);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'nova-inline-relationships');
    }
}

<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Events\ServingNova;
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
        Nova::serving(function () {
            Nova::script('nova-inline-relationship', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-inline-relationship', __DIR__ . '/../dist/css/field.css');
        });

        Field::macro('inline', function () {
            if (! class_exists(NovaInlineRelationshipHelper::getObserver($this))) {
                throw UnsupportedRelationshipType::create(class_basename($this), $this->attribute);
            }

            return NovaInlineRelationship::make($this->name, $this->attribute)->resourceClass($this->resourceClass);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}

<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
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
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-inline-relationship', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-inline-relationship', __DIR__ . '/../dist/css/field.css');
        });

        Field::macro('inline', function () {
            $className = '\\KirschbaumDevelopment\\NovaInlineRelationship\\Observers\\' . class_basename($this) . 'Observer';

            if (! class_exists($className)) {
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

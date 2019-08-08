<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use Laravel\Nova\Nova;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Fields\ResourceRelationshipGuesser;

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
            //$parent = debug_backtrace()[0]['class'];
            //dd(ResourceRelationshipGuesser::guessResource('profile'));
            $inlineClass = sprintf('%s', get_class($this));

            return NovaInlineRelationship::make($this->name, $this->attribute);
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

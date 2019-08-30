<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use Carbon\Carbon;
use App\Nova\Resource;
use Laravel\Nova\Nova;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Field;
use Illuminate\Http\UploadedFile;
use Laravel\Nova\ResourceToolElement;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Contracts\ListableField;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ResolvesReverseRelation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use KirschbaumDevelopment\NovaInlineRelationship\Rules\RelationshipRule;
use Illuminate\Database\Eloquent\Relations\Concerns\SupportsDefaultModels;
use KirschbaumDevelopment\NovaInlineRelationship\Observers\NovaInlineRelationshipObserver;

class NovaInlineRelationship extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-inline-relationship';

    public static $observedModels = [];

    private $resourceClass;

    /**
     * Pass resourceClass to NovaInlineRelationship.
     *
     * @param $class
     *
     * @return NovaInlineRelationship
     */
    public function resourceClass($class): self
    {
        $this->resourceClass = $class;

        return $this;
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     *
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        if (empty($attribute)) {
            $attribute = $this->attribute;
        }

        $models = $resource->{$attribute}()->pluck('id')->all();
        $modelKey = Str::plural(Str::kebab(class_basename($resource->{$attribute}()->first())));

        $properties = $this->getPropertiesFromResource($resource, $attribute);

        $properties = collect($properties)->map(function ($value, $key) {
            return $this->setMetaFromClass($value, $key);
        })->all();

        if ($this->isSingularRelationship($resource, $attribute)) {
            $this->value = collect($this->value ? [$this->value] : []);
        }

        $this->value = $this->value->map(function ($items, $id) use ($properties) {
            return collect($items)->map(function ($value, $key) use ($properties) {
                return ! empty($properties[$key]) ? $this->setMetaFromClass($properties[$key], $key, $value) : null;
            })->filter()->all();
        })->all();

        $this->rules = [$this->getRelationshipRule($attribute, $properties)];

        $this->withMeta([
            'defaults' => array_map(
                function ($a) {
                    return $a['default'] ?? '';
                },
                $properties
            ),
            'settings' => $properties,
            'models' => $models,
            'modelKey' => $modelKey,
            'singularLabel' => Str::title(Str::singular($attribute)),
            'pluralLabel' => Str::title(Str::plural($attribute)),
            'singular' => $this->isSingularRelationship($resource, $attribute),
            'deletable' => $this->isRelationshipDeletable($resource, $attribute),
        ]);
    }

    /**
     * Checks if a relationship is singular
     *
     * @param Model $model
     * @param $relation
     *
     * @return bool
     */
    public function isSingularRelationship(Model $model, $relation): bool
    {
        return collect(class_uses($model->{$relation}()))->contains(SupportsDefaultModels::class);
    }

    /**
     * Checks if a relationship is deletable
     *
     * @param Model $model
     * @param $relation
     *
     * @return bool
     */
    public function isRelationshipDeletable(Model $model, $relation): bool
    {
        return ! ($model->{$relation}() instanceof BelongsTo);
    }

    /**
     * Set Meta Values using Field
     *
     * @param array $item
     * @param $attrib
     * @param null $value
     *
     * @return array
     */
    protected function setMetaFromClass(array $item, $attrib, $value = null)
    {
        $attrs = ['name' => $attrib, 'attribute' => $attrib];

        /** @var Field $class */
        $class = app($item['component'], $attrs);
        $class->value = $value !== null ? $value : '';

        if (! empty($item['options']) && is_array($item['options'])) {
            $class->withMeta($item['options']);
        }

        if (! empty($item['placeholder'])) {
            $class->withMeta(['extraAttributes' => [
                'placeholder' => $item['placeholder'],
            ]]);
        }

        $item['meta'] = $class->jsonSerialize();
        // We are using Singular Label instead of name to display labels as compound name will be used in Vue
        $item['meta']['singularLabel'] = Str::singular(Str::studly($item['label'] ?? $attrib));

        $item['meta']['placeholder'] = 'Add ' . $item['meta']['singularLabel'];

        return $item;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param NovaRequest $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     *
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $response = is_array($request[$requestAttribute]) ? $request[$requestAttribute] : json_decode($request[$requestAttribute], true);

            $properties = $this->getPropertiesFromResource($model, $attribute);

            $modResponse = collect($response)->map(function ($item) use ($properties, $request) {
                return collect($item)->map(function ($value, $key) use ($properties, $item, $request) {
                    if (! empty($properties[$key])) {
                        $class = $this->getFieldClassFromProps($properties[$key], $key);

                        $temp = new \stdClass();

                        $files = collect($item)->filter(function ($itemData) {
                            return $itemData instanceof UploadedFile;
                        })->all();

                        $newRequest = NovaInlineRelationshipRequest::createFrom($request)->duplicate($item);
                        $newRequest->updateFiles($files);

                        $class->fillAttribute($newRequest, $key, $temp, $key);

                        return $temp->{$key} ?? null;
                    }

                    return $value;
                })->all();
            })->all();

            $modelClass = get_class($model);

            if (! array_key_exists($modelClass, static::$observedModels)) {
                $model::observe(NovaInlineRelationshipObserver::class);
                $model->updated_at = Carbon::now();
            }

            static::$observedModels[$modelClass][$attribute] = $this->isNullValue($modResponse) ? null : $modResponse;
        }
    }

    /**
     * @param array $props
     * @param string $key
     *
     * @return Field
     */
    protected function getFieldClassFromProps($props, $key)
    {
        $attrs = ['name' => $key, 'attribute' => $key];

        return app($props['component'], $attrs);
    }

    /**
     * Return Relationship rule from properties
     *
     * @param string $attribute
     * @param array $properties
     *
     * @return RelationshipRule
     */
    protected function getRelationshipRule($attribute, $properties): RelationshipRule
    {
        /** @var array $ruleArray */
        $ruleArray = [];

        /** @var array $messageArray */
        $messageArray = [];

        /** @var array $attribArray */
        $attribArray = [];

        foreach ($properties as $attrib => $prop) {
            if (! empty($prop['rules'])) {
                $name = sprintf('%s.*.%s', $attribute, $attrib);
                $ruleArray[$name] = $prop['rules'];
                $attribArray[$name] = $prop['label'] ?? $attrib;

                if (! empty($prop['messages']) && is_array($prop['messages'])) {
                    foreach ($prop['messages'] as $rule => $message) {
                        $messageArray[sprintf('%s.%s', $name, $rule)] = $message;
                    }
                }
            }
        }

        return new RelationshipRule($ruleArray, $messageArray, $attribArray);
    }

    /**
     * Get Properties From Resource File
     *
     * @param $model
     * @param $attribute
     *
     * @return array
     */
    protected function getPropertiesFromResource($model, $attribute): array
    {
        /** @var Resource $attribResource */
        $attribResource = ! empty($this->resourceClass) ? new $this->resourceClass($model) : Nova::newResourceFromModel($model->{$attribute}()->getRelated());

        return collect($attribResource->availableFields(new NovaRequest()))->reject(function ($field) use ($attribResource) {
            return $field instanceof ListableField ||
                $field instanceof ResourceToolElement ||
                $field->attribute === 'ComputedField' ||
                ($field instanceof ID && $field->attribute === $attribResource->resource->getKeyName()) ||
                collect(class_uses($field))->contains(ResolvesReverseRelation::class) ||
                $field instanceof self ||
                ! $field->showOnUpdate;
        })->map(function ($value, $key) {
            return ['component' => get_class($value), 'label' => $value->name, 'options' => $value->meta, 'rules' => $value->rules, 'attribute' => $value->attribute];
        })->keyBy('attribute')->toArray();
    }
}

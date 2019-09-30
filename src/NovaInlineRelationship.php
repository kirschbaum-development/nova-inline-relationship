<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use stdClass;
use Carbon\Carbon;
use App\Nova\Resource;
use Laravel\Nova\Nova;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Field;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
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

    /**
     * All the Models observed by the field
     *
     * @var array
     */
    public static $observedModels = [];

    /**
     * Name of resource class to be used
     *
     * @var string
     */
    private $resourceClass;

    /** closure to test if it can be deleted */
    protected $deleteCallback;


    /**
     * Pass resourceClass to NovaInlineRelationship.
     *
     * @param string $class
     *
     * @return NovaInlineRelationship
     */
    public function resourceClass(string $class): self
    {
        $this->resourceClass = $class;

        return $this;
    }

    /**
     * Pass closoure into the relationship to determine if it's deletable.
     * @param  callable $callback
     * @return NovaInlineRelationship
     */
    public function canDelete(callable $callback)
    {
        $this->deleteCallback = $callback;

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

        $properties = $this->getPropertiesWithMeta($resource, $attribute);

        $this->rules = [$this->getRelationshipRule($attribute, $properties)];

        $this->withMeta([
            'defaults' => $this->getDefaultsFromProperties($properties)->all(),
            'settings' => $properties->all(),
            'models' => $this->value ? $this->value->pluck('id')->all() : [],
            'modelKey' => Str::plural(Str::kebab(class_basename(optional($this->value)->first() ?? $resource->{$attribute}()->getRelated()->newInstance()))),
            'singularLabel' => Str::title(Str::singular($this->name)),
            'pluralLabel' => Str::title(Str::plural($this->name)),
            'singular' => $this->isSingularRelationship($resource, $attribute),
            'deletable' => $this->isRelationshipDeletable($resource, $attribute),
        ]);

        $this->updateFieldValue($resource, $attribute, $properties);
    }

    /**
     * Fetch default values from collection
     *
     * @param Collection $properties
     *
     * @return Collection
     */
    public function getDefaultsFromProperties(Collection $properties): Collection
    {
        return $properties->map(function ($value) {
            return $value['default'] ?? '';
        });
    }

    /**
     * Get Properties From Resource with Meta Information
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     *
     * @return Collection
     */
    public function getPropertiesWithMeta($resource, $attribute): Collection
    {
        return $this->getPropertiesFromResource($resource, $attribute)->map(function ($value, $key) {
            return $this->setMetaFromClass($value, $key);
        });
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
        if ($model->{$relation}() instanceof BelongsTo) {
            return false;
        } elseif ($this->deleteCallback) {
            return ($this->deleteCallback)($model);
        }
        return true;
    }

    /**
     * Create a duplicate request object using the base request
     *
     * @param NovaRequest $request
     * @param array $item
     *
     * @return NovaInlineRelationshipRequest
     */
    public function getDuplicateRequest(NovaRequest $request, array $item): NovaInlineRelationshipRequest
    {
        $files = collect($item)->filter(function ($itemData) {
            return $itemData instanceof UploadedFile;
        });

        // Create a New Request
        $newRequest = NovaInlineRelationshipRequest::createFrom($request)->duplicate($item, array_merge($request->all(), $item));
        // Update List of converted Files
        $newRequest->updateConvertedFiles($files);

        return $newRequest;
    }

    /**
     * Get Value for the Child attribute from field
     *
     * @param Field $field
     * @param NovaInlineRelationshipRequest $request
     * @param string $attribute
     *
     * @return mixed|null
     */
    public function getValueFromField(Field $field, NovaInlineRelationshipRequest $request, string $attribute)
    {
        $temp = new stdClass();

        // Fill Attributes in Field
        $field->fillAttribute($request, $attribute, $temp, $attribute);

        return $temp->{$attribute} ?? null;
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

        if (! empty($item['readonly'])) {
            $class->readonly($item['readonly']);
        }

        $item['meta'] = $class->jsonSerialize();
        // We are using Singular Label instead of name to display labels as compound name will be used in Vue
        $item['meta']['singularLabel'] = Str::title(Str::singular(str_replace('_', ' ', $item['label'] ?? $attrib)));

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

            $modResponse = $this->getResourceResponse($request, $response, $properties);

            if ($model instanceof Model) {
                $this->setModelAttributeValue($model, $attribute, $modResponse);
            }
        }
    }

    /**
     * Resolve Field class from child Resource attribute
     *
     * @param array $props
     * @param string $key
     *
     * @return Field
     */
    protected function getResourceField($props, $key): Field
    {
        $attrs = ['name' => $key, 'attribute' => $key];

        return resolve($props['component'], $attrs);
    }

    /**
     * Return Relationship rule from properties
     *
     * @param string $attribute
     * @param Collection $properties
     *
     * @return RelationshipRule
     */
    protected function getRelationshipRule($attribute, Collection $properties): RelationshipRule
    {
        /** @var array $ruleArray */
        $ruleArray = [];

        /** @var array $messageArray */
        $messageArray = [];

        /** @var array $attribArray */
        $attribArray = [];

        $properties->each(function ($child, $childAttribute) use ($attribute, &$ruleArray, &$messageArray, &$attribArray) {
            if (! empty($child['rules'])) {
                $name = sprintf('%s.*.%s', $attribute, $childAttribute);
                $ruleArray[$name] = $child['rules'];
                $attribArray[$name] = $child['label'] ?? $childAttribute;

                if (! empty($child['messages']) && is_array($child['messages'])) {
                    foreach ($child['messages'] as $rule => $message) {
                        $messageArray[sprintf('%s.%s', $name, $rule)] = $message;
                    }
                }
            }
        });

        return new RelationshipRule($ruleArray, $messageArray, $attribArray);
    }

    /**
     * Get Properties From Resource File
     *
     * @param $model
     * @param $attribute
     *
     * @return Collection
     */
    protected function getPropertiesFromResource($model, $attribute): Collection
    {
        /** @var Resource $resource */
        $resource = ! empty($this->resourceClass) ? new $this->resourceClass($model) : Nova::newResourceFromModel($model->{$attribute}()->getRelated());

        $request = app(NovaRequest::class);

        return collect($resource->availableFields(new NovaRequest()))->reject(function ($field) use ($resource) {
            return $field instanceof ListableField ||
                $field instanceof ResourceToolElement ||
                $field->attribute === 'ComputedField' ||
                ($field instanceof ID && $field->attribute === $resource->resource->getKeyName()) ||
                collect(class_uses($field))->contains(ResolvesReverseRelation::class) ||
                $field instanceof self ||
                ! $field->showOnUpdate;
        })->reject(function($field){
            return $field->seeCallback && !($field->seeCallback)(request());
        })->map(function ($value) use ($request) {
            return [
                'component' => get_class($value),
                'label' => $value->name,
                'options' => $value->meta,
                'rules' => $value->rules,
                'attribute' => $value->attribute,
                'readonly' => $value->isReadonly($request),
            ];
        })->keyBy('attribute');
    }

    /**
     * Update value for the field
     *
     * @param $resource
     * @param $attribute
     * @param Collection $properties
     */
    protected function updateFieldValue($resource, $attribute, Collection $properties): void
    {
        if ($this->isSingularRelationship($resource, $attribute)) {
            $this->value = collect($this->value ? [$this->value] : []);
        }

        $this->value = $this->value->map(function ($items) use ($properties) {
            return collect($items)->map(function ($value, $key) use ($properties) {
                return $properties->has($key) ? $this->setMetaFromClass($properties->get($key), $key, $value) : null;
            })->filter();
        });
    }

    /**
     * Generate response object for a child resource by passing request to each field
     *
     * @param NovaRequest $request
     * @param $response
     * @param Collection $properties
     *
     * @return array
     */
    protected function getResourceResponse(NovaRequest $request, $response, Collection $properties): array
    {
        return collect($response)->map(function ($item) use ($properties, $request) {
            return collect($item)->map(function ($value, $key) use ($properties, $request, $item) {
                if ($properties->has($key)) {
                    $field = $this->getResourceField($properties->get($key), $key);

                    $newRequest = $this->getDuplicateRequest($request, $item);

                    return $this->getValueFromField($field, $newRequest, $key) ?? ((($field instanceof File) && ! empty($value)) ? $value : null);
                }

                return $value;
            })->all();
        })->all();
    }

    /**
     * save model attribute to a static array
     *
     * @param Model $model
     * @param $attribute
     * @param array $value
     */
    protected function setModelAttributeValue(Model $model, $attribute, array $value): void
    {
        $modelClass = get_class($model);

        if (! array_key_exists($modelClass, static::$observedModels)) {
            $model::observe(NovaInlineRelationshipObserver::class);
            $model->updated_at = Carbon::now();
        }

        static::$observedModels[$modelClass][$attribute] = $this->isNullValue($value) ? null : $value;
    }
}
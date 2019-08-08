<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use App\Nova\Resource;
use Laravel\Nova\Nova;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Illuminate\Http\UploadedFile;
use Laravel\Nova\Http\Requests\NovaRequest;
use KirschbaumDevelopment\NovaInlineRelationship\Rules\RelationshipRule;
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

        $attribResource = Nova::newResourceFromModel($resource->{$attribute}()->first());
        $properties = collect($attribResource->fields(request()))->map(function ($value, $key) {
            return ['component' => get_class($value), 'label' => $value->name, 'options' => $value->meta, 'rules' => $value->rules, 'attribute' => $value->attribute];
        })->keyBy('attribute')->toArray();

        //$propMap = $resource::getPropertyMap();
        //$properties = $propMap[$attribute];

        //dd($attribFields);

        $properties = collect($properties)->map(function ($value, $key) {
            return $this->setMetaFromClass($value, $key);
        })->all();

        $this->value = collect($this->value)->map(function ($items, $id) use ($properties) {
            return collect($items)->map(function ($value, $key) use ($properties) {
                return $this->setMetaFromClass($properties[$key] ?? [], $key, $value);
            })->all();
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
            'singular' => $resource->isSingularRelationship($attribute),
        ]);
    }

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

            //$propMap = $model::getPropertyMap();
            //$properties = $propMap[$attribute ?? $this->attribute];
            $attribResource = Nova::newResourceFromModel($model->{$attribute}()->first());
            $properties = collect($attribResource->fields(request()))->map(function ($value, $key) {
                return ['component' => get_class($value), 'label' => $value->name, 'options' => $value->meta, 'rules' => $value->rules, 'attribute' => $value->attribute];
            })->keyBy('attribute')->toArray();

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

            $model->{$attribute} = $this->isNullValue($modResponse) ? null : $modResponse;
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
}

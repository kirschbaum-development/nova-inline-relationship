<?php

namespace KirschbaumDevelopment\NovaInlineRelationship;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use KirschbaumDevelopment\NovaInlineRelationship\Rules\RelationshipRule;

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
        $propMap = $resource::getPropertyMap();
        $properties = $propMap[$attribute ?? $this->attribute];

        $this->rules = [$this->getRelationshipRule($attribute ?? $this->attribute, $properties)];

        $this->withMeta([
            'defaults' => array_map(
                function ($a) {
                    return $a['default'] ?? '';
                },
                $properties
            ),
            'settings' => $properties,
            'singular' => $resource->isSingularRelationship($attribute ?? $this->attribute),
        ]);

        parent::resolve($resource, $attribute);
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
            $value = json_decode($request[$requestAttribute], true);
            $model->{$attribute} = $this->isNullValue($value) ? null : $value;
        }
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

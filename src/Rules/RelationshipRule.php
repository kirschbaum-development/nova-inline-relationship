<?php

namespace KirschbaumDevelopment\NovaInlineRelationship\Rules;

use Illuminate\Support\MessageBag;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RelationshipRule implements Rule
{
    public $rules = [];

    /**
     * @var array
     */
    protected $messages;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var MessageBag
     */
    protected $response;

    /**
     * Create a new rule instance.
     *
     * @param array $rules
     * @param null|mixed $messages
     * @param null|mixed $attributes
     *
     */
    public function __construct(array $rules, $messages = null, $attributes = null)
    {
        $this->rules = $rules;
        $this->messages = $messages;
        $this->attributes = $attributes;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $input = [$attribute => is_array($value) ? $value : json_decode($value, true)];

        $validator = Validator::make($input, $this->rules, $this->messages, $this->attributes);

        $this->response = array_map(function ($message) {
            return is_array($message) ? $message[0] ?? '' : $message;
        }, $validator->errors()->getMessages());

        return $validator->passes();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->response;
    }
}

# Nova Inline Relationship

## Introduction
Nova Inline Relationship is meant to present a relationship based property as an inline property for a Laravel Nova Resource. This project is under active development, and currently only supports singular relationships. You are welcome to request or contribute by opening an issue.


## Requirements

This Nova resource tool requires Nova 2.0 or higher.

## Installation

You can install this package in a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require kirschbaum-development/nova-inline-relationship
```

## Setup

After installation, your model should include the `KirschbaumDevelopment\NovaInlineRelationship\Traits\HasRelatedAttributes` trait and you must implement the `KirschbaumDevelopment\NovaInlineRelationship\Contracts\MappableRelationships` Contract.

You must also define a static `getPropertyMap` function in the model which should return the required mapping between local and related attribute.

**_NOTE:_** You must add relationships in `relationship.attribute` format in the map. Nested relationships are currently not supported.

```php
use KirschbaumDevelopment\NovaInlineRelationship\Traits\HasRelatedAttributes;
use KirschbaumDevelopment\NovaInlineRelationship\Contracts\MappableRelationships;

class Employee extends Model implements MappableRelationships
{
    use HasRelatedAttributes;

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    /**
     * Should return property map as key value pair.
     *
     * @return array
     */
    public static function getPropertyMap(): array
    {
        return ['phone' => 'profile.phone', 'fax' => 'profile.fax'];
    }
    
    // ...
}
````

## Usage

Once you add this relationship map you can use the keys for this relationship map as a normal attribute inside your model's nova resource. 

**_NOTE:_** These fields are in essence [Computed Fields](https://nova.laravel.com/docs/2.0/resources/fields.html#computed-fields), and are subjected to the same limitations. Since they are not associated with a database column, these fields will not be `sortable`.

```php
namespace App\Nova;

class Employee extends Resource
{
    
    //...
    public function fields(Request $request)
    {
        return [
            //...

            Text::make('Phone #', 'phone')
                ->rules('required'),

            Number::make('Fax Number', 'fax'),
        ];
    }
}
``` 

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email brandon@kirschbaumdevelopment.com or nathan@kirschbaumdevelopment.com instead of using the issue tracker.

## Sponsorship

Development of this package is sponsored by Kirschbaum Development Group, a developer driven company focused on problem solving, team building, and community. Learn more [about us](https://kirschbaumdevelopment.com) or [join us](https://careers.kirschbaumdevelopment.com)!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

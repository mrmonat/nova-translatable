# Nova Spatie Translatable Field
[![Latest Version on Packagist](https://img.shields.io/packagist/v/mrmonat/nova-translatable.svg?style=flat-square)](https://packagist.org/packages/mrmonat/nova-translatable)

Adds the ability to show and edit translated fields created with [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) package.

It will show up in the detail view like this:

<img width="400" src="https://mrmonat.de/github/images/nova-spatie-translatable-details.png">

And in the edit view like this:

<img width="400" src="https://mrmonat.de/github/images/nova-spatie-translatable-edit.png">

## Installation and usage
You can require this package using composer:

```
composer require mrmonat/nova-translatable
```

You can add the field follows:

```
use MrMonat\Translatable\Translatable;

Translatable::make('Description'),
```

### Defining Locales
Locales can be defined via config file ```config/translatable.php``` (config file can be created via [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) package) by adding a ```locales``` array:

```
// config/translatable.php
return [
    ...
    'locales' => [
        'en' => 'English',
        'de' => 'German',
        'fr' => 'French',
    ],
];
```

Alternatively you can "override" the config locales with the ```locales(...)``` method:

```
Translatable::make('Description')->locales([
    'en' => 'English',
    'de' => 'German',
]),
```

### Single Line Option
By default the input field on the edit view is a textarea. If you want to change it to a single line input field you can add the ```singleLine``` option:

```
Translatable::make('Description')->locales([...])->singleLine(),
```

### Trix Editor
You can use the trix editor for your translated fields by using the ```trix()``` option:

```
Translatable::make('Description')->trix(),
```

### Index View
By default the locale used when displaying the field on the index view is determined by ```app()->getLocale()```. To override this you can use the ```indexLocale($locale)``` option:

```
Translatable::make('Description')->indexLocale('de'),
```


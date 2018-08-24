# Nova Spatie Translatable Field

Adds the ability to show end edit translated fields created with [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable) package.

It will show up in the detail view like this:

<img width="400" src="https://mrmonat.de/github/images/nova-spatie-translatable-details.png">

And in the edit view like this:

<img width="400" src="https://mrmonat.de/github/images/nova-spatie-translatable-edit.png">

## Installation and usage

You can require this package using composer:

```
composer require mrmonat/nova-spatie-translatable
```

You can add the field with defined locales to show as follows:

```
use MrMonat\Translatable\Translatable;

Translatable::make('Description')->locales([
    'en' => 'English',
    'de' => 'German',
    'fr' => 'French',
]);
```

The array key for example ```en``` has to match your locale key when using the translation package. The array value for example ```English``` defines label text of the locale.


# Nova Spatie Translatable Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mrmonat/nova-spatie-translatable.svg?style=flat-square)](https://packagist.org/packages/mrmonat/nova-spatie-translatable)

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

#### Single Line Option

By default the input field on the edit view is a textarea. If you want to change it to a single line input field you can add the ```singleLine``` option:

```
Translatable::make('Description')->locales([...])->singleLine();
```


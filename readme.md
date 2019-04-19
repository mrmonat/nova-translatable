# Nova Translatable Field
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

Make sure, that you have your Eloquent model setup correct:

- First, you need to add the `Spatie\Translatable\HasTranslations`-trait.
- Next, you should create a public property `$translatable` which holds an array with all the names of attributes you wish to make translatable.
- Finally, you should make sure that all translatable attributes are set to the `text`-datatype in your database. If your database supports `json`-columns, use that.

Here's an example of a prepared model:

``` php
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsItem extends Model
{
    use HasTranslations;
    
    public $translatable = ['name'];
}
```


### Defining Locales
Locales can be defined via config file ```config/translatable.php``` (config file can be created via [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable#installation) package) by adding a ```locales``` array:

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
By default the input field on the edit view is a textarea. If you want to change it to a single line input field you can add the ```singleLine()``` option:

```
Translatable::make('Description')->locales([...])->singleLine(),
```

### Trix Editor
You can use the trix editor for your translated fields by using the ```trix()``` option:

```
Translatable::make('Description')->trix(),
```


### TipTap Editor with Mention Plugin

You can use the tiptap editor for your translated fields by using the ```tiptap()``` option.

In addtion the mention / suggestions plugin has been integrated, see demo on https://tiptap.scrumpy.io/suggestions

```
Translatable::make('Message')
->tiptap()
->buttons(['bold', 'italic', 'heading', 'link', 'ordered_list', 'bullet_list', 'blockquote', 'undo', 'redo'])
->help(__('Press @ to insert mentions'))
,
```


You need to implement a controller, which delivers the mention / suggestions:


```
class MentionItemsController
{
    public function index(NovaRequest $request)
    {
        // available parameters
        $request->resource;
        $request->field;

        return [
            ['id' => 1, "name" => 'user.firstname']
        ];


    }
}
```

The following code snippet transforms the mention fields into a mustache placeholder
```
$string = preg_replace('#<span class="mention"([^>]*?)>@(.*?)</span>#', '{{$2}}', $string);
```
which you can parse using `$mustache->render()`



### Index View
By default the locale used when displaying the field on the index view is determined by ```app()->getLocale()```. To override this you can use the ```indexLocale($locale)``` option:

```
Translatable::make('Description')->indexLocale('de'),
```


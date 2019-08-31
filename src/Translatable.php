<?php

namespace MrMonat\Translatable;

use Laravel\Nova\Fields\Field;

class Translatable extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'translatable';

    /**
     * Create a new field.
     *
     * @param string $name
     * @param string|null $attribute
     * @param mixed|null $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $locales = array_map(function ($value) {
            return __($value);
        }, config('translatable.locales'));

        $this->withMeta([
            'locales' => $locales,
            'indexLocale' => app()->getLocale()
        ]);
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param mixed $resource
     * @param string $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        if (method_exists($resource, 'getTranslations')) {
            return $resource->getTranslations($attribute);
        }
        return data_get($resource, $attribute);
    }

    /**
     * Set the locales to display / edit.
     *
     * @param array $locales
     * @return $this
     */
    public function locales(array $locales)
    {
        return $this->withMeta(['locales' => $locales]);
    }

    /**
     * Set the locale to display on index.
     *
     * @param string $locale
     * @return $this
     */
    public function indexLocale($locale)
    {
        return $this->withMeta(['indexLocale' => $locale]);
    }

    /**
     * Set the input field to a single line text field.
     */
    public function singleLine()
    {
        return $this->withMeta(['singleLine' => true]);
    }

    /**
     * Use Trix Editor.
     */
    public function trix()
    {
        return $this->withMeta(['trix' => true]);
    }


    /**
     * Use Trix Editor.
     */
    public function tags()
    {
        return $this->withMeta(['tags' => true]);
    }

    /**
     * use tiny editor
     * @param array $config
     * @return mixed
     */
    public function tiny($config = [])
    {
        $this->asHtml();

        return $this->withMeta(['tiny' => true, 'tiny_config' =>
            array_merge([
                'path_absolute' => '/',
                'plugins' => [
                    'lists preview hr anchor pagebreak',
                    'wordcount fullscreen',
                    'contextmenu directionality',
                    'paste textcolor colorpicker textpattern'
                ],
                'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
                'relative_urls' => false,
                'use_lfm' => false,
                'lfm_url' => 'laravel-filemanager'
            ], $config)
        ]);
    }

    /**
     * Display the field as raw HTML.
     */
    public function asHtml()
    {
        return $this->withMeta(['asHtml' => true]);
    }

    /**
     * Truncate on Detail Page.
     */
    public function truncate()
    {
        return $this->withMeta(['truncate' => true]);
    }
}

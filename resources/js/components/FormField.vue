<template>
    <default-field :field="field">
        <template slot="field">
            <a 
                class="inline-block font-bold cursor-pointer mr-2" 
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                :key="localeKey" 
                v-for="(locale, localeKey) in field.locales"
                @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <textarea :id="field.name + '-' + localeKey"
                class="mt-4 w-full form-control form-input form-input-bordered py-3 min-h-textarea"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value[localeKey]"
                :key="localeKey"
                v-for="(locale, localeKey) in field.locales"
                v-show="localeKey === currentLocale"
                v-if="!field.singleLine"
            ></textarea>

            <input type="text" :id="field.name + '-' + localeKey"
                class="mt-4 w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value[localeKey]"
                :key="localeKey"
                v-for="(locale, localeKey) in field.locales"
                v-show="localeKey === currentLocale"
                v-if="field.singleLine"
            ></textarea>

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            currentLocale: Object.keys(this.field.locales)[0]
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
          this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            Object.keys(this.value).forEach(locale => {
                formData.append(this.field.attribute + '[' + locale + ']', this.value[locale] || '')
            })
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
          this.value = value
        },

        changeTab(locale) {
            this.currentLocale = locale
        }
    }
}
</script>

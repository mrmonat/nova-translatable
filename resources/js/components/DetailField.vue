<template>
    <panel-item :field="field">
        <template slot="value">

            <a 
                class="inline-block font-bold cursor-pointer mr-2" 
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                :key="localeKey" 
                v-for="(locale, localeKey) in field.locales"
                @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <div 
                class="mt-4"
                :key="localeKey" 
                v-for="(localeValue, localeKey) in field.value"
                v-show="localeKey === currentLocale"
                v-html="localeValue"
            ></div>

        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    data() {
        return {
            currentLocale: Object.keys(this.field.locales)[0]
        }
    },

    methods: {
        changeTab(locale) {
            this.currentLocale = locale
        }
    }
}
</script>

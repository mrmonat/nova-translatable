<template>
    <panel-item :field="field">
        <template slot="value">

            <a 
                class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none border-primary" 
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                :key="`a-${localeKey}`" 
                v-for="(locale, localeKey) in field.locales"
                @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <div class="mt-4">
                <span v-if="field.value[currentLocale]" v-html="field.value[currentLocale]"></span>
                <span v-else>—</span>
            </div>

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

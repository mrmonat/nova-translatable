<template>
    <panel-item :field="field">
        <template slot="value">

            <a 
                class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none" 
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
                v-for="(locale, localeKey) in field.locales"
                v-show="localeKey === currentLocale"
            >
                <div v-if="field.value[localeKey]" v-html="field.value[localeKey]"></div>
                <div v-else>—</div>
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

    mounted() {
        console.log(this.field.value)
    },

    methods: {
        changeTab(locale) {
            this.currentLocale = locale
        }
    }
}
</script>

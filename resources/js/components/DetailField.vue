<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="mt-4">
                <div v-if="field.asHtml" v-html="value"></div>
                <div v-else :class="{ 'truncate': field.truncate }">{{ value }}</div>
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
        this.currentLocale = document.querySelector('#select-language-translatable').value;
        Nova.$on('change-language', (lang) => {
            this.changeTab(lang);
        });
    },

    methods: {
        changeTab(locale) {
            this.currentLocale = locale
        }
    },

    computed: {
        value() {
            return this.field.value[this.currentLocale] || '—'
        }
    },
}
</script>

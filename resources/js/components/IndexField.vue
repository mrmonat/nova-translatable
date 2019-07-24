<template>
    <div v-if="field.asHtml" v-html="value"></div>
    <span v-else class="whitespace-no-wrap">{{ value }}</span>
</template>

<script>
    export default {
        props: ['resourceName', 'field'],
        mounted() {
            this.language = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.language = lang;
            });
        },
        data() {
            return {
                language: null
            }
        },
        computed: {
            value() {
                return this.field.value[this.language] || '—'
            }
        }
    }
</script>
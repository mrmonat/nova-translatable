<template>
    <div v-if="field.asHtml" v-html="value"></div>
    <span v-else class="whitespace-no-wrap">{{ value }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
    computed: {
        value() {
            return this.field.value[this.field.indexLocale] || '—'
        }
    }
}
</script>

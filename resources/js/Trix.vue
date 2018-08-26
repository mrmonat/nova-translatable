<script>
import 'trix/dist/trix.css'

export default {
    name: 'trix-vue',
    props: ['name', 'value', 'placeholder'],
    methods: {
        update() {
            this.$refs.theEditor.editor.loadHTML(this.value)
        },
        onInitialize() {
            this.$refs.theEditor.editor.insertHTML(this.value)
        },
        onChange() {
            this.$emit('change', this.$refs.theEditor.value)
        },
    }
}
</script>

<template>
    <trix-editor
        ref="theEditor"
        @trix-change="onChange"
        @trix-initialize="onInitialize"
        @trix-file-accept="e => e.preventDefault()"
        :value="value"
        :placeholder="placeholder"
    />
</template>

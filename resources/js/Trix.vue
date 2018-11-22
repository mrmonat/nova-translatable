<script>
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import { quillEditor } from 'vue-quill-editor';

export default {
    name: 'trix-vue',
    props: ['name', 'value', 'placeholder'],
    components: {
        quillEditor
    },
    data() {
        return {
            editorOption: {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote'],
                        [{'header': [1, 2, 3, 4, 5, 6, false]}],
                        [{'list': 'ordered'}, {'list': 'bullet'}],
                        [{'color': []}, {'background': []}],
                        [{'align': ['right', 'justify']}],
                        ['clean'],
                        ['link', 'image', 'video']
                    ],
                },
                placeholder: this.placeholder
            },
            content: null,
            init: false
        }
    },

    mounted: () => {
        this.content = this.value;
    },

    methods: {
        update() {
            this.content = this.value;
        },

        onChange() {
            this.$emit('change', this.$refs.theEditor.value)
        },
    },

    watch: {
        'value': function(newValue) {
            if(!this.init) {
                this.content = this.value;
                this.init = true;
            }
        }
    }
}
</script>

<template>

    <quill-editor ref="theEditor"
                  v-model="content"
                  :options="editorOption"
                  @change="onChange">
    </quill-editor>
</template>

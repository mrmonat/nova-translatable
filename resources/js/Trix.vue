<script>
    import Editor from '@tinymce/tinymce-vue';

    export default {
        name: 'trix-vue',
        props: ['name', 'value', 'placeholder'],
        components: {
            'editor': Editor
        },
        data() {
            return {
                content: null,
                init: false,
                config: {
                    entity_encoding : "raw",
                    encoding: "UTF-8",
                    menubar: false,
                    plugins: "textcolor preview link",
                    toolbar: "undo redo | formatselect | bold italic strikethrough underline forecolor backcolor | link | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link",
                    forced_root_block: "div"
                }
            }
        },

        mounted: () => {
            this.content = this.value;
        },

        methods: {
            update() {
                this.content = this.value == undefined ? '' : this.value;
            },

            onChange() {
                this.$emit('change', this.content)
            },
        },

        watch: {
            'value': function (newValue) {
                if (!this.init) {
                    this.content = this.value;
                    this.init = true;
                }
            },
            'content': function(newValue) {
                this.$emit('change', this.content);
            }
        }
    }
</script>

<template>
    <editor v-model="content"
            ref="theEditor"
            @change="onChange"
            api-key="qw068v96pacv2vfc9nc69wnpkc3h3jzdsz643l6ioup1icd7"
            :init="config"
    ></editor>
</template>

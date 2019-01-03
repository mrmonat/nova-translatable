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
                    menubar: false,
                    plugins: "textcolor emoticons preview",
                    toolbar: "undo redo | formatselect | bold italic strikethrough underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link | emoticons"
                }
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

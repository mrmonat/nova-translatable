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
            'value': function (newValue) {
                if (!this.init) {
                    this.content = this.value;
                    this.init = true;
                }
            }
        }
    }
</script>

<template>
    <editor v-model="content" ref="theEditor" @change="onChange" api-key="qw068v96pacv2vfc9nc69wnpkc3h3jzdsz643l6ioup1icd7"></editor>
</template>

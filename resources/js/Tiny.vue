<template>
    <editor ref="theEditor" :api-key="tinymce_api_key"
            :id="name"
            v-model="value"
            :placeholder="placeholder"
            :init="options"
            @onChange="onChange"
            :class="errorClasses"
    ></editor>
</template>

<script>
    import Editor from '@tinymce/tinymce-vue'

    export default {
        components: {Editor},
        props: ['name', 'value', 'placeholder', 'tiny_config'],
        computed: {
            tinymce_api_key() {
                return Nova.config.tinymce_api_key
            },

            options() {
                let options = this.tiny_config;
                if (options.use_lfm) {
                    options['file_browser_callback'] = this.filemanager
                }
                return options
            }
        },
        methods: {
            setInitialValue() {
                this.value = '';
            },
            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value;
                this.$emit('change', value)
            },
            filemanager(field_name, url, type, win) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
                let cmsURL = this.options.path_absolute + this.options.lfm_url + '?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + '&type=Images';
                } else {
                    cmsURL = cmsURL + '&type=Files';
                }
                tinyMCE.activeEditor.windowManager.open({
                    file: cmsURL,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    resizable: 'yes',
                    close_previous: 'no'
                });
            },
            update() {
                this.value = this.value ? this.value : '';
            },
            onChange() {
                this.$emit('change', this.value)
            },
        }
    }
</script>
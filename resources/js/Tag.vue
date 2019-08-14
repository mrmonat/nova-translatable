<template>
    <div>
        <v-tags-input
                v-model="tag"
                :tags="tags"
                @tags-changed="onChange"
                :placeholder="placeholder"
                :class="errorClasses"
        >
        </v-tags-input>
    </div>
</template>

<script>
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
        components: {"v-tags-input": VueTagsInput},
        props: ['name', 'value', 'placeholder'],
        computed: {},
        data() {
            return {
                tag: '',
                tags: []
            };
        },
        watch: {
            value: function (value) {
               this.update();
            },
        },
        methods: {
            update() {
                this.tags = this.value ? this.value.split(',').map(item => {
                    return {text: item};
                }) : [];
            },
            onChange(newTags) {
                this.tags = newTags;
                this.$emit('change', this.tags ? this.tags.map(item => {
                    return item.text;
                }).join(',') : '')
            },
        }
    }
</script>
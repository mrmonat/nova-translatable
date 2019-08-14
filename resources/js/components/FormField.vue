<template>
    <field-wrapper>
        <div class="w-1/5 px-8 py-6">
            <slot>
                <form-label :for="field.name">
                    {{ field.name }}
                </form-label>
            </slot>
        </div>
        <div class="px-8 py-6" :class="computedWidth">
            <a
                    class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none border-primary"
                    :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                    :key="`a-${localeKey}`"
                    v-for="(locale, localeKey) in field.locales"
                    @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>

            <textarea
                    ref="field"
                    :id="field.name"
                    class="mt-4 w-full form-control form-input form-input-bordered py-3 min-h-textarea"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model="value[currentLocale]"
                    v-if="isTextArea"
                    @keydown.tab="handleTab"
            ></textarea>

            <div v-if="isTrix" @keydown.stop class="mt-4">
                <trix
                        ref="field"
                        name="trixman"
                        :value="value[currentLocale]"
                        placeholder=""
                        @change="handleChange"
                />
            </div>

            <div v-if="isTiny" class="mt-4">
                <tiny
                        ref="field"
                        :name="field.attribute"
                        :value="value[currentLocale]"
                        :placeholder="field.name"
                        :tiny_config="field.tiny_config"
                        @change="handleChange"
                />
            </div>

            <input
                    ref="field"
                    type="text"
                    :id="field.name"
                    class="mt-4 w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name"
                    v-model="value[currentLocale]"
                    v-if="isSingle"
                    @keydown.tab="handleTab"
            />

            <tag
                    v-if="isTags"
                    class="mt-4 w-full form-control"
                    ref="field"
                    :name="field.attribute"
                    :value="value[currentLocale]"
                    :placeholder="field.name"
                    @change="handleChange"
            />

            <p v-if="hasError" class="my-2 text-danger">
                {{ firstError }}
            </p>
            <help-text class="help-text mt-2" v-if="field.helpText">
                {{ field.helpText }}
            </help-text>
        </div>
    </field-wrapper>
</template>

<script>

    import Trix from '../Trix'
    import Tiny from '../Tiny'
    import Tag from '../Tag'

    import {FormField, HandlesValidationErrors} from 'laravel-nova'

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            Trix,
            Tag,
            Tiny
        },
        data() {
            return {
                locales: Object.keys(this.field.locales),
                currentLocale: null,
            }
        },

        mounted() {
            this.currentLocale = this.locales[0] || null
        },

        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || {}
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                Object.keys(this.value).forEach(locale => {
                    formData.append(this.field.attribute + '[' + locale + ']', this.value[locale] || '')
                })
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value[this.currentLocale] = value
            },

            changeTab(locale) {
                this.currentLocale = locale
                this.$nextTick(() => {
                    if (this.isTrix || this.isTiny || this.isTags) {
                        this.$refs.field.update()
                    } else {
                        this.$refs.field.focus()
                    }
                })
            },

            handleTab(e) {
                const currentIndex = this.locales.indexOf(this.currentLocale)
                if (!e.shiftKey) {
                    if (currentIndex < this.locales.length - 1) {
                        e.preventDefault()
                        this.changeTab(this.locales[currentIndex + 1])
                    }
                } else {
                    if (currentIndex > 0) {
                        e.preventDefault()
                        this.changeTab(this.locales[currentIndex - 1])
                    }
                }
            }
        },

        computed: {
            computedWidth() {
                return {
                    'w-1/2': !this.field.trix || !this.field.tiny,
                    'w-4/5': this.field.trix || this.field.tiny
                }
            },
            isTextArea() {
                return !this.field.singleLine && !this.field.trix && !this.field.tiny && !this.field.tags;
            },
            isSingle() {
                return this.field.singleLine;
            },
            isTrix() {
                return this.field.trix;
            },
            isTiny() {
                return this.field.tiny;
            },
            isTags() {
                return this.field.tags;
            }
        }
    }
</script>

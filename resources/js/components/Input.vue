<template>
    <div>
        <a
                class="inline-block font-bold cursor-pointer mr-2 animate-text-color select-none border-primary"
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary border-b-2': localeKey === currentLocale }"
                :key="`a-${localeKey}`"
                v-for="(locale, localeKey) in locales"
                @click="changeTab(localeKey)"
        >
            {{ locale }}
        </a>

        <textarea
                ref="field"
                :id="name"
                class="mt-4 w-full form-control form-input form-input-bordered py-3 min-h-textarea"
                :class="errorClasses"
                :placeholder="name"
                v-model="text[currentLocale]"
                v-if="isTextarea"
                @keydown.tab="handleTab"
        ></textarea>

        <div v-if="isTrix" @keydown.stop class="mt-4">
            <trix
                    ref="field"
                    name="trixman"
                    :value="text[currentLocale]"
                    placeholder=""
                    @change="handleChange"
            />
        </div>

        <input
                ref="field"
                type="text"
                :id="name"
                class="mt-4 w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="name"
                v-model="text[currentLocale]"
                v-if="isSingleLine"
                @keydown.tab="handleTab"
        />

        <p v-if="hasError" class="my-2 text-danger">
            {{ firstError }}
        </p>
        <help-text class="help-text mt-2" v-if="helpText">
            {{ helpText }}
        </help-text>
    </div>
</template>

<script>
    import Trix from '../Trix'
    import {forEach} from 'lodash'

    export default {
        components: { Trix },

        props: ['locales', 'name', 'type', 'helpText', 'value', 'selectedLocale'],

        data() {
            return {
                currentLocale: null,
                text: []
            }
        },

        mounted() {
            this.currentLocale = this.locales[this.selectedLocale] || null;
        },

        computed: {
            isTrix() {
                return !!this.currentLocale  && this.type == 'trix';
            },
            isSingleLine() {
                return !!this.currentLocale  && this.type == 'single_line';
            },
            isTextarea() {
                return !!this.currentLocale  && this.type == 'textarea';
            },
            computedWidth() {
                return {
                    'w-1/2': !this.isTrix,
                    'w-4/5': this.isTrix
                }
            },
        },

        methods: {
            changeTab(locale) {
                console.log(locale);
                this.currentLocale = locale;
            }
        }
    }
</script>
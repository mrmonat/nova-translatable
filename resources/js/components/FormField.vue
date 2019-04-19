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
                    v-if="!field.singleLine && !field.trix && !field.tiptap"
                @keydown.tab="handleTab"
            ></textarea>

            <div v-if="!field.singleField && field.trix" @keydown.stop class="mt-4">
                <trix
                    ref="field"
                    name="trixman"
                    :value="value[currentLocale]"
                        placeholder="field.helpText"
                    @change="handleChange"
                />
            </div>


            <div v-if="!field.singleField && field.tiptap" @keydown.stop class="mt-4">


                <editor-menu-bar :editor="editor">
                    <div class="menubar" slot-scope="{ commands, isActive, getMarkAttrs }">
                        <template v-for="(buttonKey, params) in buttons">
                            <template v-if="buttonKey == 'heading' || params == 'heading'">
                                <heading-buttons
                                        :headingLevels="headingLevels"
                                        :commands="commands"
                                        :isActive="isActive"
                                >
                                </heading-buttons>
                            </template>

                            <template v-if="buttonKey != 'heading' && buttonKey != 'link' && params != 'heading'">
                                <normal-button
                                        :buttonKey="buttonKey"
                                        :commands="commands"
                                        :isActive="isActive"
                                >
                                </normal-button>
                            </template>

                            <span class="tiptap-button-container" v-if="buttonKey == 'link'">
                            <link-button
                                    :commands="commands"
                                    :isActive="isActive"
                                    :linkMenuIsActive="linkMenuIsActive"
                                    :linkUrl="linkUrl"
                                    :hideLinkMenu="hideLinkMenu"
                                    :showLinkMenu="showLinkMenu"
                                    :getMarkAttrs="getMarkAttrs"
                                    :setLinkUrl="setLinkUrl"
                            >
                            </link-button>
                            </span>

                        </template>


                    </div>
                </editor-menu-bar>

                <editor-content
                        class=" tiptap-content
                                py-3 h-auto
                                pr-6
                                pb-4
                                pt-4
                                w-full
                                form-control
                                form-input
                                form-input-bordered
                                mt-2
                                no-focus "
                        :editor="editor"
                />

                <div class="suggestion-list" v-show="showSuggestions" ref="suggestions">
                    <template v-if="hasResults">
                        <div v-for="(placeholderField, index) in filteredPlaceholderFields"
                             :key="placeholderField.id"
                             class="suggestion-list__item"
                             :class="{ 'is-selected': navigatedPlaceholderFieldIndex === index }"
                             @click="selectPlaceholderField(placeholderField)"
                        > {{ placeholderField.name }}
                        </div>
                    </template>
                    <div v-else class="suggestion-list__item is-empty">
                        No fields found
                    </div>
                </div>


            </div>


            <input 
                ref="field" 
                type="text" 
                :id="field.name"
                class="mt-4 w-full form-control form-input form-input-bordered"
                :class="errorClasses"
                :placeholder="field.name"
                v-model="value[currentLocale]"
                v-if="field.singleLine"
                @keydown.tab="handleTab"
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

import { FormField, HandlesValidationErrors } from 'laravel-nova'

    import tippy from 'tippy.js'
    // import Icon from 'components/Icon'

    import storage from "../storage/MentionItemStorage"

    import {Editor, EditorContent, EditorMenuBar, EditorMenuBubble} from 'tiptap'
    import HeadingButtons from './buttons/HeadingButtons';
    import NormalButton from './buttons/NormalButton';
    import LinkButton from './buttons/LinkButton';

    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        OrderedList,
        BulletList,
        HorizontalRule,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
        Mention
    } from 'tiptap-extensions'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

        components: {
            Trix,
            EditorContent,
            EditorMenuBar,
            // Icon,
            HeadingButtons,
            NormalButton,
            LinkButton,
        },

    data() {
        return {

                availableMentionItems: [],


            locales: Object.keys(this.field.locales),
            currentLocale: null,

                headingLevels: 3,

                linkUrl: null,

                linkMenuIsActive: false,

                editor: new Editor({
                    extensions: [
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({levels: [1, 2, 3]}),
                        new HorizontalRule(),
                        new ListItem(),
                        new OrderedList(),
                        // new TodoItem(),
                        // new TodoList(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Link(),
                        new Strike(),
                        new Underline(),
                        new History(),
                        new Mention({
                            // a list of all suggested items
                            items: () => this.availableMentionItems,
                            // is called when a suggestion starts
                            onEnter: ({
                                          items, query, range, command, virtualNode,
                                      }) => {
                                this.query = query
                                this.filteredPlaceholderFields = items
                                this.suggestionRange = range
                                this.renderPopup(virtualNode)
                                // we save the command for inserting a selected mention
                                // this allows us to call it inside of our custom popup
                                // via keyboard navigation and on click
                                this.insertMention = command
                            },
                            // is called when a suggestion has changed
                            onChange: ({
                                           items, query, range, virtualNode,
                                       }) => {
                                this.query = query
                                this.filteredPlaceholderFields = items
                                this.suggestionRange = range
                                this.navigatedPlaceholderFieldIndex = 0
                                this.renderPopup(virtualNode)
                            },
                            // is called when a suggestion is cancelled
                            onExit: () => {
                                // reset all saved values
                                this.query = null
                                this.filteredPlaceholderFields = []
                                this.suggestionRange = null
                                this.navigatedPlaceholderFieldIndex = 0
                                this.destroyPopup()
                            },
                            // is called on every keyDown event while a suggestion is active
                            onKeyDown: ({event}) => {
                                // pressing up arrow
                                if (event.keyCode === 38) {
                                    this.upHandler()
                                    return true
                                }
                                // pressing down arrow
                                if (event.keyCode === 40) {
                                    this.downHandler()
                                    return true
                                }
                                // pressing enter
                                if (event.keyCode === 13) {
                                    this.enterHandler()
                                    return true
                                }
                                return false
                            }

                        }),
                    ],
                    content: this.localeContent
                }),
                query: null,
                suggestionRange: null,
                filteredPlaceholderFields: [],
                navigatedPlaceholderFieldIndex: 0,
                insertMention: () => {
                },
                observer: null,
        }
    },

    mounted() {
        this.currentLocale = this.locales[0] || null

            this.initEditor();
            this.initMentionItems();

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
                if (this.field.trix) {
                    this.$refs.field.update()
                    } else if (this.field.tiptap) {
                        this.editor.focus()
                        var content = '';
                        if (typeof this.value[this.currentLocale] !== 'undefined') {
                            content = this.value[this.currentLocale];
                        }
                        this.editor.setContent(content)
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
            },

            initMentionItems() {
                storage.fetchAvailableItems(this.resourceName, this.field.attribute, {params: {}})
                    .then(({data: items}) => {
                        this.availableMentionItems = items;
                    });
            },


            initEditor() {
                this.editor.setContent(this.value[this.currentLocale]);

                // set the value each time editor is updated
                let outsideScope = this;
                this.editor.setOptions({
                    onUpdate: function (state) {
                        outsideScope.value[outsideScope.currentLocale] = state.getHTML();
                    }
                });

                // set heading levels
                if (this.field.headingLevels) {
                    this.headingLevels = this.field.headingLevels;
                } else {
                    // fallback for the old style like this: 'heading' => 4
                    _.forEach(this.buttons, function (params, key) {
                        if (key == 'heading') {
                            this.headingLevels = params;
                        }
                    }.bind(this));
                }

            },

            showLinkMenu(attrs) {
                this.linkUrl = attrs.href;
                this.linkMenuIsActive = true;
            },

            hideLinkMenu() {
                this.linkUrl = null;
                this.linkMenuIsActive = false;
            },

            setLinkUrl(command, url) {
                command({href: url});
                this.hideLinkMenu();
                this.editor.focus();
            },


            // navigate to the previous item
            // if it's the first item, navigate to the last one
            upHandler() {
                this.navigatedPlaceholderFieldIndex = ((this.navigatedPlaceholderFieldIndex + this.filteredPlaceholderFields.length) - 1) % this.filteredPlaceholderFields.length
            },
            // navigate to the next item
            // if it's the last item, navigate to the first one
            downHandler() {
                this.navigatedPlaceholderFieldIndex = (this.navigatedPlaceholderFieldIndex + 1) % this.filteredPlaceholderFields.length
            },
            enterHandler() {
                const placeholderField = this.filteredPlaceholderFields[this.navigatedPlaceholderFieldIndex]
                if (placeholderField) {
                    this.selectPlaceholderField(placeholderField)
        }
    },
            // we have to replace our suggestion text with a mention
            // so it's important to pass also the position of your suggestion text
            selectPlaceholderField(placeholderField) {
                this.insertMention({
                    range: this.suggestionRange,
                    attrs: {
                        id: placeholderField.id,
                        label: placeholderField.name,
                    },
                })
                this.editor.focus()
            },
            // renders a popup with suggestions
            // tiptap provides a virtualNode object for using popper.js (or tippy.js) for popups
            renderPopup(node) {
                if (this.popup) {
                    return
                }
                this.popup = tippy(node, {
                    content: this.$refs.suggestions,
                    trigger: 'mouseenter',
                    interactive: true,
                    theme: 'dark',
                    placement: 'top-start',
                    inertia: true,
                    duration: [400, 200],
                    showOnInit: true,
                    arrow: true,
                    arrowType: 'round',
                })


                // we have to update tippy whenever the DOM is updated
                if (MutationObserver) {
                    this.observer = new MutationObserver(() => {
                        this.popup.popperInstance.scheduleUpdate()
                    })
                    this.observer.observe(this.$refs.suggestions, {
                        childList: true,
                        subtree: true,
                        characterData: true,
                    })
                }

            },
            destroyPopup() {
                if (this.popup) {
                    this.popup.destroy()
                    this.popup = null
                }
                if (this.observer) {
                    this.observer.disconnect()
                }
            },


        },

    computed: {
        computedWidth() {
            return {
                'w-1/2': !this.field.trix,
                    'w-4/5': this.field.trix,
                    'w-4/5': this.field.tiptap
            }
            },
            buttons() {
                return this.field.buttons ? this.field.buttons : ['bold', 'italic'];
            },

            hasResults() {
                return this.filteredPlaceholderFields.length
            },
            showSuggestions() {
                return this.query || this.hasResults
        }
    }
}
</script>


<style>
    .tiptap-content {
        outline: none !important;
        box-shadow: none !important;
    }

    .ProseMirror:focus {
        outline: none;
    }

    .ProseMirror ins {
        text-decoration: none;
        background-color: highlight;
    }

    .ProseMirror p, ul, ol, blockquote, pre {
        margin-bottom: 1em;
    }

    .ProseMirror h1, .ProseMirror h2, .ProseMirror h3, .ProseMirror h4, .ProseMirror h5, .ProseMirror h6 {
        margin-bottom: 0.5em;
    }

    .ProseMirror pre {
        white-space: pre-line;
    }

    .ProseMirror blockquote {
        border-left: 4px solid var(--60);
        padding-left: 12px;
        margin-left: 0;
        font-style: italic;
    }

    .ProseMirror p:last-child {
        margin-bottom: 0;
    }

    .tiptap-button {
        box-sizing: border-box;
        vertical-align: top;
        margin-right: 8px;
        font-weight: 400;
    }

    .tiptap-button-container {
        position: relative;
        overflow: visible;
    }

    .tiptap-button-form {
        position: absolute;
        top: 36px;
        left: -130px;
        display: flex;
        background-color: white;
        align-items: center;
    }

    .tiptap-button-form .form-input {
        width: 180px;
        margin-right: 20px;
        color: black;
    }

    .tiptap-button-form .btn {
        width: 16px;
        height: 16px;
    }


    .tiptap-button::before {
        margin: 0;
        height: 16px;
        line-height: 0;
        position: relative;
    }

    .tiptap-button.is-bold::before {
        content: 'B';
    }

    .tiptap-button.is-italic::before {
        font-style: italic;
        content: 'I';
    }

    .tiptap-button.is-strike::before {
        text-decoration: line-through;
        content: 'S';
    }

    .tiptap-button.is-underline::before {
        text-decoration: underline;
        content: 'U';
    }

    .tiptap-button.is-code::before {
        content: '<>';
    }

    .tiptap-button.is-code_block::before {
        content: '</>';
    }


</style>

<style lang="scss">

    $color-black: #000;
    $color-white: #fff;

    .mention {
        background: rgba($color-black, 0.1);
        color: rgba($color-black, 0.6);
        font-size: 0.8rem;
        /*font-weight: bold;*/
        border-radius: 5px;
        padding: 0.2rem 0.5rem;
        white-space: nowrap;
    }

    .mention-suggestion {
        color: rgba($color-black, 0.6);
    }

    .suggestion-list {
        padding: 0.2rem;
        border: 2px solid rgba($color-black, 0.1);
        font-size: 0.8rem;
        font-weight: bold;

        &__no-results {
            padding: 0.2rem 0.5rem;
        }

        &__item {
            border-radius: 5px;
            padding: 0.2rem 0.5rem;
            margin-bottom: 0.2rem;
            cursor: pointer;

            &:last-child {
                margin-bottom: 0;
            }

            &.is-selected,
            &:hover {
                background-color: rgba($color-white, 0.2);
            }

            &.is-empty {
                opacity: 0.5;
            }
        }
    }

    .tippy-tooltip.dark-theme {
        background-color: $color-black;
        padding: 0;
        font-size: 1rem;
        text-align: inherit;
        color: $color-white;
        border-radius: 5px;

        .tippy-backdrop {
            display: none;
        }

        .tippy-roundarrow {
            fill: $color-black;
        }

        .tippy-popper[x-placement^=top] & .tippy-arrow {
            border-top-color: $color-black;
        }

        .tippy-popper[x-placement^=bottom] & .tippy-arrow {
            border-bottom-color: $color-black;
        }

        .tippy-popper[x-placement^=left] & .tippy-arrow {
            border-left-color: $color-black;
        }

        .tippy-popper[x-placement^=right] & .tippy-arrow {
            border-right-color: $color-black;
        }
    }
</style>
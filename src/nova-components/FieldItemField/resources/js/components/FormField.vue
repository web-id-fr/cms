<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <multiselect
                        v-model="selected"
                        :options="field.items"
                        title="Search an item field"
                        label="title"
                        :custom-label="customLabel"
                        track-by="title"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :taggable="true"
                    ></multiselect>
                </div>

                <div class="flex flex-col mt-3">
                    <draggable v-model="selected" ghost-class="ghost">
                        <div
                            class="shadow-md p-3 my-2"
                            v-for="element in selected"
                            :key="element.id"
                        >
                            {{ selectFirstTitle(element.title, element.formable_type) }}
                        </div>
                    </draggable>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova';
    import Multiselect from 'vue-multiselect'
    import draggable from 'vuedraggable';
    import {map} from 'lodash';

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            Multiselect,
            draggable,
        },

        data() {
            return {
                selected: [],
                currentLocale: null,
            }
        },

        computed: {},

        mounted() {
            this.currentLocale = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.currentLocale = lang;
            });
        },

        methods: {

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '')
            },

            /*
            * Set the initial, internal value for the field.
            */
            setInitialValue() {
                this.value = this.field.value || '';
                this.selected = this.value || [];
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value;
            },

            customLabel({title, formable_type}) {
                return this.selectFirstTitle(title, formable_type);
            },

            selectFirstTitle(title, formable_type) {
                let type;

                if(formable_type === 'Webid\\Cms\\Modules\\Form\\Models\\TitleField'){
                    type = 'Title  | '
                } else {
                    type = 'Field | '
                }
                if(typeof title === 'string'){
                    return type + title;
                }
                if (!title[this.currentLocale]) {
                    if (title[this.currentLocale + 1]) {
                        return type + title[this.currentLocale + 1];
                    } else if (title[this.currentLocale - 1]) {
                        return type +title[this.currentLocale - 1];
                    } else {
                        return type + title[Object.keys(title)[0]];
                    }
                } else {
                    return type + title[this.currentLocale];
                }
            }
        },

        watch: {
            selected: function (val) {
                let ids = map(val, (item) => {
                    return {
                        id: item.id,
                        formable_type: item.formable_type
                    };
                });
                this.handleChange(JSON.stringify(ids));
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

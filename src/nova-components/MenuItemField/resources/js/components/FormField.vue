<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <multiselect
                        v-model="selected"
                        :options="field.items"
                        placeholder="Search an item"
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
                    <div class="justify-content-between row">
                        <nested-test class="col-8" v-model="selected" />
                    </div>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova';
    import NestedTest from "./nested-test.vue";
    import Multiselect from 'vue-multiselect'
    import draggable from 'vuedraggable';
    import {map} from 'lodash';

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            Multiselect,
            draggable,
            NestedTest,
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

            customLabel ({ title }) {
                return this.selectFirstTitle(title);
            },

            selectFirstTitle(title) {
                if(!title[this.currentLocale]) {
                    if(title[this.currentLocale + 1]) {
                        return title[this.currentLocale + 1];
                    } else if(title[this.currentLocale - 1]) {
                        return title[this.currentLocale -1];
                    } else {
                        return title[Object.keys(title)[0]];
                    }
                } else {
                    return title[this.currentLocale];
                }
            }
        },

        watch: {
            selected: function(val) {
                let ids = map(val, (item) => {
                    return {
                        id: item.id,
                        menuable_type: item.menuable_type,
                        children: item.children
                    };
                });
                this.handleChange(JSON.stringify(ids));
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

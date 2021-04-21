<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex flex-col">
                <Modal
                    ref="modal"
                    :menus="options"
                    :active="activeModal"
                    v-on:closeModal="closeModal"
                    v-on:selectMenuItems="selectMenuItems"
                    :search="search"
                    :selected="selected"
                />

                <div class="flex flex-col">
                    <button @click.prevent="showModalCreator($event)"
                            class="btn btn-default btn-primary block m-3 w-1/2 mx-auto">
                        <svg class="mx-1 align-middle" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                  clip-rule="evenodd"/>
                        </svg>
                        {{ __("Add a menu item") }}
                    </button>
                </div>

                <multiselect v-model="selected"/>

                <div class="flex flex-col mt-3">
                    <div class="justify-content-between row">
                        <nested-rows
                            v-on:selectMenuItems="selectMenuItems"
                            class="col-8"
                            v-model="selected"
                        />
                    </div>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova';
    import NestedRows from "./nested-rows.vue";
    import Modal from './modules/Modal';
    import Multiselect from 'vue-multiselect'
    import draggable from 'vuedraggable';
    import {map} from 'lodash';
    import {mapChildren, successToast, errorToast} from "../helpers";

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            Multiselect,
            draggable,
            NestedRows,
            Modal: Modal,
        },

        data() {
            return {
                selected: [],
                options: [],
                currentLocale: null,
                activeModal: false,
            }
        },

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
                this.options = this.field.items || [];
            },

            changeMenuItems(newMenuItems) {
                this.value = newMenuItems;
                if (this.field) {
                    Nova.$emit(this.field.attribute + '-change', this.value);
                }
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value;
            },

            closeModal() {
                this.activeModal = false;
            },

            showModalCreator(e) {
                this.stopDefaultActions(e);
                this.activeModal = true;
            },

            stopDefaultActions(e) {
                e.preventDefault();
                e.stopPropagation();
            },

            selectMenuItems(menuItem) {
                const findIndex = _.findIndex(this.selected, menuItem);

                let selected = this.selected.findIndex(function (elem) {
                    return (_.isEqual(elem.title, menuItem.title) && _.isEqual(elem.menuable_type, menuItem.menuable_type));
                });

                if (selected >= 0) {
                    this.options.filter(function (currentElement) {
                        if (_.isEqual(currentElement.title, menuItem.title) && _.isEqual(currentElement.menuable_type, menuItem.menuable_type)) {
                            currentElement.children = [];
                            return currentElement;
                        }
                    });

                    this.selected.splice(findIndex, 1);
                    errorToast(this.__('The menu item has been removed to the list'));
                    return;
                } else {
                    for (const [key, value] of Object.entries(this.selected)) {
                        if (_.findIndex(value.children, {
                            'title': menuItem.title,
                            'menuable_type': menuItem.menuable_type
                        }) >= 0) {
                            value.children.splice(_.findIndex(value.children, menuItem), 1);
                            errorToast(this.__('The menu item has been removed to the list'));
                            return;
                        }
                    }

                    this.selected.push(menuItem);
                    successToast(this.__('The menu item has been added to the list'));
                }
            },
        },

        watch: {
            selected: {
                handler: function (val, oldVal) {
                    this.changeMenuItems(val);
                    let ids = map(val, (item) => {
                        return {
                            id: item.id,
                            menuable_type: item.menuable_type,
                            children: mapChildren(item)
                        };
                    });
                    this.handleChange(JSON.stringify(ids));
                },
                deep: true
            },
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

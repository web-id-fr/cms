<template>
    <div class="flex flex-col">
        <Modal
            ref="modal"
            :components="options"
            :active="activeModal"
            v-on:closeModal="closeModal"
            v-on:selectComponents="selectComponents"
            :search="search"
            :selected="selected"
        />

        <multiselect
            v-model="selected"
        />

        <div class="flex flex-col mt-3 block m-auto w-1/2">
            <div class="text-lg m-3">
                {{ __("My page") }}
            </div>

            <draggable v-model="selected" ghost-class="ghost">
                <div class="shadow-md p-3 my-2 flex flex-col"
                     v-for="element in selected"
                     :key="element.id"
                >
                    <div class="w-full flex justify-between">
                        <div class="flex-auto self-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="20" height="20" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <div class="flex-auto self-center text-center py-2 m-2">
                            <p class="break-words">{{ element.name }}</p>
                        </div>
                        <div class="flex-auto self-center">
                            <img class="m-auto block" width="200px" :src="element.component_image">
                        </div>
                        <div class="flex-auto self-center text-center py-2 m-2">
                            <a v-tooltip.click="__('Edit')" target="_blank" :href="element.component_nova + '/' + element.id + '/edit'"
                               class="inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                                    <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                                </svg>
                            </a>
                            <button v-tooltip.click="__('Detach')" v-on:click="selectComponents(element)" class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3 has-tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </draggable>

            <button @click.prevent="showModalCreator($event)"
                    class="btn btn-default btn-primary block m-3 w-1/2 mx-auto">
                <svg class="mx-1 align-middle" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                {{ __("Add a component") }}
            </button>
        </div>
    </div>
</template>

<script>
    import {FormField, HandlesValidationErrors} from 'laravel-nova';
    import Multiselect from 'vue-multiselect';
    import draggable from 'vuedraggable';
    import {map} from 'lodash';

    import Modal from './modules/Modal';

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            Multiselect,
            draggable,
            Modal: Modal,
        },

        data() {
            return {
                selected: [],
                options: [],
                activeModal: false,
            }
        },

        methods: {
            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                formData.append(this.field.attribute, this.value || '');
            },

            /*
            * Set the initial, internal value for the field.
            */
            setInitialValue() {
                this.value = this.field.value || '';
                this.selected = this.value || [];
                this.options = this.field.items || [];
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

            selectComponents(component) {
                const findIndex = _.findIndex(this.selected, component);

                if (findIndex >= 0) {
                    this.selected.splice(findIndex, 1);
                    return;
                }

                if (this.selected.indexOf(component) === -1) {
                    this.selected.push(component);
                }
            },
        },

        watch: {
            selected: function (val) {
                let ids = map(val, (item) => {
                    return {
                        id: item.id,
                        component_type: item.component_type,
                        component_nova: item.component_nova,
                        component_image: item.component_image
                    };
                });
                this.handleChange(JSON.stringify(ids));
            }
        }
    }
</script>

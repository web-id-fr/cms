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
            <div class="text-lg mb-3">
                {{ __("My page") }}
            </div>

            <draggable v-model="selected" ghost-class="ghost">
                <div class="relative flex bg-white mb-2 pb-1"
                     v-for="element in selected"
                     :key="element.id"
                >
                    <div class="z-10 bg-white border-t border-l border-b border-60 h-auto pin-l pin-t rounded-l self-start w-8">
                        <div>
                            <button v-tooltip.click="__('Move up')" type="button" class="group-control btn border-t border-r border-40 w-8 h-8 bloc has-tooltip" @click.prevent="moveUp(element)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 20 20" stroke="grey">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                </svg>
                            </button>
                            <button v-tooltip.click="__('Move down')" type="button" class="group-control btn border-t border-r border-40 w-8 h-8 block has-tooltip" @click.prevent="moveDown(element)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 20 20" stroke="grey">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <button v-tooltip.click="__('Detach')" @click.prevent="selectComponents(element)" class="group-control btn border-t border-r border-40 w-8 h-8 block has-tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                    <path fill="grey" fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                                </svg>
                            </button>
                            <button v-tooltip.click="__('Edit')" target="_blank" @click.prevent="openEditPage(element)" class="group-control btn border-t border-r border-40 w-8 h-8 block has-tooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                                    <path fill="grey" d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex min-h-full w-full border-60 border rounded-r-lg">
                        <div class="flex-auto self-center text-center py-2 m-2">
                            <p class="break-words">{{ element.name }}</p>
                        </div>
                        <div class="flex-auto self-center">
                            <img class="m-auto block" width="180px" :src="element.component_image">
                        </div>
                    </div>
                </div>
            </draggable>

            <button @click.prevent="showModalCreator($event)" class="btn btn-default btn-primary block m-3 w-1/2 mx-auto">
                <svg class="mx-1 align-middle" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
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

            moveUp(component) {
                let findIndex = _.findIndex(this.selected, component);

                if (findIndex <= 0) return;

                this.selected.splice(findIndex - 1, 0, this.selected.splice(findIndex, 1)[0]);
            },

            moveDown(component) {
                let findIndex = _.findIndex(this.selected, component);

                if (findIndex < 0 || findIndex >= this.selected.length - 1) return;

                this.selected.splice(findIndex + 1, 0, this.selected.splice(findIndex, 1)[0]);
            },

            openEditPage(component) {
                window.open(component.component_nova + "/" + component.id + "/edit", "_blank");
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

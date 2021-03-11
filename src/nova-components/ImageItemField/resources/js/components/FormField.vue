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
                        track-by="id"
                        :multiple="true"
                        :close-on-select="true"
                        :clear-on-select="true"
                        :taggable="true"
                    >
                        <template slot="option" slot-scope="props">
                            <div class="flex bg-gray-200">
                                <div>
                                    <img width="100px" class="option__image" :src="props.option.imageAsset">
                                </div>
                                <div class="self-center text-gray-700 text-center px-4 py-2 m-2">
                                    <div class="option__desc">
                                        <span class="option__title">{{ selectFirstTitle(props.option.title) }}</span>
                                    </div>
                                </div>
                            </div>

                        </template>
                    </multiselect>
                </div>

                <div class="flex flex-col mt-3">
                    <draggable v-model="selected"
                               ghost-class="ghost">
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
                                    <button v-tooltip.click="__('Detach')" @click.prevent="deselectComponents(element)" class="group-control btn border-t border-r border-40 w-8 h-8 block has-tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                            <path fill="grey" fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex min-h-full w-full border-60 border rounded-r-lg">
                                <div class="flex-auto self-center">
                                    <img class="m-auto block" width="180px" :src="element.imageAsset">
                                </div>
                                <div class="flex-auto self-center text-center py-2 m-2">
                                    <p class="break-words">{{ selectFirstTitle(element.title) }}</p>
                                </div>
                            </div>
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

            customLabel({title}) {
                return this.selectFirstTitle(title);
            },

            selectFirstTitle(title) {
                if (!title[this.currentLocale]) {
                    if (title[this.currentLocale + 1]) {
                        return title[this.currentLocale + 1];
                    } else if (title[this.currentLocale - 1]) {
                        return title[this.currentLocale - 1];
                    } else {
                        return title[Object.keys(title)[0]];
                    }
                } else {
                    return title[this.currentLocale];
                }
            },

            moveUp(slide) {
                let findIndex = _.findIndex(this.selected, slide);

                if (findIndex <= 0) return;

                this.selected.splice(findIndex - 1, 0, this.selected.splice(findIndex, 1)[0]);
            },

            moveDown(slide) {
                let findIndex = _.findIndex(this.selected, slide);

                if (findIndex < 0 || findIndex >= this.selected.length - 1) return;

                this.selected.splice(findIndex + 1, 0, this.selected.splice(findIndex, 1)[0]);
            },

            deselectComponents(slide) {
                const findIndex = _.findIndex(this.selected, slide);

                if (findIndex >= 0) {
                    this.selected.splice(findIndex, 1);
                }
            },
        },

        watch: {
            selected: function (val) {
                let ids = map(val, (item) => {
                    return {
                        id: item.id,
                        imageAsset: item.imageAsset
                    };
                });
                this.handleChange(JSON.stringify(ids));
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

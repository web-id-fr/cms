<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <multiselect
                        v-model="selected"
                        :options="field.items"
                        placeholder="Search an item"
                        label="name"
                        track-by="name"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :taggable="true"
                    ></multiselect>
                </div>

                <div class="flex flex-col mt-3">
                    <draggable v-model="selected"
                               ghost-class="ghost">
                        <div class="shadow-md p-3 my-2 flex justify-between"
                            v-for="element in selected"
                            :key="element.id"
                        >
                            <div class="flex-auto self-center text-center py-2 m-2">
                                <p class="break-words">{{ element.name }}</p>
                            </div>
                            <div class="flex-auto">
                                <img class="m-auto block" width="200px" :src="element.component_image">
                            </div>
                            <div class="flex-auto self-center text-center py-2 m-2">
                                <a target="_blank" :href="element.component_nova + '/' + element.id + '/edit'">
                                    <font-awesome-icon icon="pen"/>
                                </a>
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
    import Multiselect from 'vue-multiselect';
    import draggable from 'vuedraggable';
    import {map} from 'lodash';

    import {library} from '@fortawesome/fontawesome-svg-core';
    import {faPen} from '@fortawesome/free-solid-svg-icons';
    import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

    library.add(faPen);

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        components: {
            Multiselect,
            draggable,
            'font-awesome-icon': FontAwesomeIcon,
        },

        data() {
            return {
                selected: []
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
            },

            /**
             * Update the field's internal value.
             */
            handleChange(value) {
                this.value = value;
            },
        },

        watch: {
            selected: function(val) {
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

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

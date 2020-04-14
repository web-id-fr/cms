<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <multiselect
                        v-model="selected"
                        :options="field.items"
                        title="Search an item field"
                        label="name"
                        track-by="name"
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
                            {{ element.name }}
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
            }
        },

        computed: {},

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
        },

        watch: {
            selected: function (val) {
                let ids = map(val, (item) => {
                    return {
                        id: item.id,
                    };
                });
                this.handleChange(JSON.stringify(ids));
            }
        }
    }
</script>

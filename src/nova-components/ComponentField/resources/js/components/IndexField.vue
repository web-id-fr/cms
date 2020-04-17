<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="flex flex-col">
                <div class="flex flex-col">
                    <label class="inline-block text-80 pt-2 leading-tight">Select items</label>
                    <multiselect
                        v-model="selected"
                        :options="field.items"
                        placeholder="Search an item"
                        label="name"
                        track-by="id"
                        :multiple="true"
                        :close-on-select="false"
                        :clear-on-select="false"
                        :taggable="true"
                    ></multiselect>
                </div>

                <div class="flex flex-col mt-3">
                    <label class="inline-block text-80 pt-2 leading-tight">Component used</label>
                    <draggable v-model="selected"
                               ghost-class="ghost">
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
    export default {
        props: ['resourceName', 'field'],
    }
</script>

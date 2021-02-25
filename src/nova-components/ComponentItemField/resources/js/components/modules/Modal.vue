<template>
    <modal v-if="active">
        <div class="bg-white rounded-lg shadow-lg" style="width: 70vw;">
            <div class="flex flex-wrap border-b border-70">
                <div class="w-3/4 px-4 py-3 ">
                    {{ __('Components') }}
                </div>

                <div class="w-1/4 flex flex-wrap justify-end">
                    <button class="btn buttons-actions m-3" v-on:click="closeModal">X</button>
                </div>
            </div>

            <div class="w-full flex flex-wrap">
                <div class="relative w-1/2 max-w-xs mt-3 mx-3" v-if="activeComponentItem">
                    <button class="btn btn-default btn-primary inline-flex items-center" v-on:click="hideComponentItems">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="pr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        {{ __('Back') }}
                    </button>
                </div>
                <div class="relative w-1/2 max-w-xs mt-3 mx-3 justify-end" v-if="!activeComponentItem">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         class="fill-current absolute search-icon-center ml-3 text-70">
                        <path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                    </svg>
                    <input v-on:input="searchItems" v-model="search" type="search" :placeholder="this.__('Search')"
                           class="pl-search form-control form-input form-input-bordered w-full">
                </div>
            </div>

            <div class="flex flex-wrap m-3" v-if="!activeComponentItem">
                <template v-for="(component, name, index) in filteredFiles">
                    <Component :component="component"
                               class="h-40 folder-item"
                               v-on:showComponentItems="showComponentItems"
                               :name="name"
                    />
                </template>
            </div>

            <ComponentItem
                ref="componentItem"
                :componentItem="componentItem"
                :active="activeComponentItem"
                v-on:selectComponent="selectComponent"
                :selected="selected"
                :name="componentName"
            />
        </div>
    </modal>
</template>

<script>
    import Component from "./Component";
    import ComponentItem from "./ComponentItem";

    export default {
        props: {
            active: {
                type: Boolean,
                default: false,
                required: true,
            },
            components: {
                type: Object,
                required: true,
            },
            search: {
                type: String,
                required: false,
                default: '',
            },
            selected: {
                type: Array,
                default: () => [],
                required: false,
            },
        },

        data() {
            return {
                search: '',
                activeComponentItem: false,
                componentItem: {},
                componentName: '',
            }
        },

        components: {
            Component,
            ComponentItem: ComponentItem,
        },

        methods: {
            showComponentItems(componentItem, componentName) {
                this.activeComponentItem = true;
                this.componentItem = componentItem;
                this.componentName = componentName;
            },

            hideComponentItems() {
                this.componentItem = {};
                this.activeComponentItem = false;
            },

            closeModal() {
                this.hideComponentItems();
                this.$emit('closeModal');
            },

            selectComponent(component) {
                this.$emit('selectComponents', component);
            },

            searchItems: _.debounce(function (e) {
                this.search = e.target.value;
            }, 300),
        },

        computed: {
            filteredFiles() {
                let filtered = {};

                if (this.search) {
                    for (const [key, value] of Object.entries(this.components)) {
                        let title = this.__(key);
                        if (title.toLowerCase().includes(this.search.toLowerCase())) {
                            filtered[key] = value;
                        }
                    }

                    return filtered;
                }

                return this.components;
            }
        },
    };
</script>


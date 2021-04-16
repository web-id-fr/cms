<template>
    <modal v-if="active">
        <div class="bg-white rounded-lg shadow-lg overflow-auto" style="width: 70vw;height: 70vh">
            <div class="flex flex-wrap border-b border-70">
                <div class="w-3/4 px-4 py-3 ">
                    {{ __("Menu items") }}
                </div>

                <div class="w-1/4 flex flex-wrap justify-end">
                    <button class="btn buttons-actions m-3" v-on:click="closeModal">X</button>
                </div>
            </div>

            <div class="w-full flex flex-wrap">
                <div class="relative w-1/2 max-w-xs mt-3 mx-3 mb-3 justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         class="fill-current absolute search-icon-center ml-3 text-70">
                        <path fill-rule="nonzero"
                              d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                    </svg>
                    <input v-on:input="searchItems" v-model="search" type="search" :placeholder="this.__('Search')"
                           class="pl-search form-control form-input form-input-bordered w-full"
                           ref="search-components">
                </div>
            </div>

            <ComponentItem
                ref="componentItem"
                :menuItems="filteredFiles"
                v-on:selectMenuItem="selectMenuItem"
                :selected="selected"
            />
        </div>
    </modal>
</template>

<script>
    import ComponentItem from "./ComponentItem";

    export default {
        props: {
            active: {
                type: Boolean,
                default: false,
                required: true,
            },
            menus: {
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
                currentLocale: null,
            }
        },

        mounted() {
            this.currentLocale = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.currentLocale = lang;
            });
        },

        components: {
            ComponentItem: ComponentItem,
        },

        updated() {
            if (this.$refs['search-components']) {
                this.$refs['search-components'].focus();
            }
        },

        methods: {
            closeModal() {
                this.$emit('closeModal');
            },

            selectMenuItem(menuItem) {
                this.$emit('selectMenuItems', menuItem);
            },

            searchItems: _.debounce(function (e) {
                this.search = e.target.value;
            }, 300),
        },

        computed: {
            filteredFiles() {
                let filtered = {};

                if (this.search) {
                    for (const [key, menu] of Object.entries(this.menus)) {
                        if (menu.title && menu.title[this.currentLocale]) {
                            let title = menu.title[this.currentLocale];
                            if (title.toLowerCase().includes(this.search.toLowerCase())) {
                                filtered[key] = menu;
                            }
                        }
                    }
                    return filtered;
                }

                return this.menus;
            }
        },
    };
</script>


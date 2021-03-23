<template>
    <div>
        <heading class="mb-6">{{ __('Components') }}</heading>

        <card class="flex items-stretch flex-wrap p-8" style="min-height: 300px">
            <div class="w-full flex flex-wrap">
                <div class="relative w-1/2 max-w-xs mt-3 mx-3 justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         class="fill-current absolute search-icon-center ml-3 text-70">
                        <path fill-rule="nonzero" d="M14.32 12.906l5.387 5.387a1 1 0 0 1-1.414 1.414l-5.387-5.387a8 8 0 1 1 1.414-1.414zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                    </svg>
                    <input v-on:input="searchItems" v-model="search" type="search" :placeholder="this.__('Search')"
                           class="pl-search form-control form-input form-input-bordered w-full"
                           ref="search-components" autofocus>
                </div>
            </div>
            <div class="w-1/3 py-4 px-8" v-for="component in filteredFiles" :key="component.title">
                <a :href="component.nova" class="no-underline dim text-primary font-bold">
                    <div class="min-h-full flex flex-col text-center">
                        <div class="m-4">
                            <h3>{{ __(component.title) }}</h3>
                        </div>
                        <div class="h-64">
                            <img :src="component.image">
                        </div>
                    </div>
                </a>
            </div>
        </card>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getComponents();
            this.$refs['search-components'].focus();
        },

        data: () => ({
            components: [],
            search: '',
        }),

        methods: {
            getComponents() {
                Nova.request().get('/ajax/component').then((response) => {
                    this.components = response.data;
                })
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
                        let title = this.__(value['title']);
                        if (title.toLowerCase().includes(this.search.toLowerCase())) {
                            filtered[key] = value;
                        }
                    }

                    return filtered;
                }

                return this.components;
            }
        },
    }
</script>

<template>
    <div>
        <heading class="mb-6">{{ __('Components') }}</heading>

        <card class="flex items-stretch flex-wrap p-8" style="min-height: 300px">
            <div class="w-1/3 py-4 px-8" v-for="component in components" :key="component.title">
                <a :href="component.nova">
                    <div class="min-h-full flex flex-col text-center">
                        <div class="m-4">
                            <h3>{{ component.title }}</h3>
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
        },

        data: () => ({
            components: [],
        }),

        methods: {
            getComponents() {
                Nova.request().get('/ajax/component').then((response) => {
                    this.components = response.data;
                })
            },
        },
    }
</script>

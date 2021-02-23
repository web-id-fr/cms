<template>
    <div class="flex flex-wrap" v-if="active">
        <div class="w-1/6 m-3 relative flex border border-lg border-50" style="min-height: 8rem">
            <img class="m-auto block" width="200px" :src="componentItem[0]['component_image']" alt="">
            <div class="w-full bg-50 text-center text-xs flex items-center justify-center p-1 absolute text-name">
                {{ name }}
            </div>
        </div>
        <table class="table custom-table w-full table-auto">
            <thead>
                <tr>
                    <th class="w-12">
                    </th>
                    <th class="text-left">
                        {{ __('Name') }}
                    </th>
                    <th class="text-left">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="component in componentItem">
                    <td v-on:click="select(component)">
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             class="block">
                            <g v-if="isSelected(component)">
                                <rect width="20" height="20" rx="4" fill="var(--primary)"></rect>
                                <path fill="#FFF" d="M7.7 9.3c-.23477048-.3130273-.63054226-.46037132-1.01285927-.37708287-.38231702.08328846-.68093514.38190658-.7642236.7642236C5.83962868 10.0694577 5.9869727 10.4652295 6.3 10.7l2 2c.38884351.3811429 1.01115649.3811429 1.4 0l4-4c.3130273-.23477048.4603713-.63054226.3770829-1.01285927-.0832885-.38231702-.3819066-.68093514-.7642236-.7642236C12.9305423 6.83962868 12.5347705 6.9869727 12.3 7.3L9 10.58l-1.3-1.3v.02z"></path>
                            </g>
                            <g v-else>
                                <rect width="20" height="20" fill="#FFF" rx="4"></rect>
                                <rect width="19" height="19" fill="none" x=".5" y=".5" stroke="#CCD4DB" rx="4"></rect>
                            </g>
                        </svg>
                    </td>
                    <td v-on:click="select(component)">
                        {{ __(component.name) }}
                    </td>
                    <td class="td-fit text-right pr-6 align-middle">
                        <div class="inline-flex items-center">
                            <span class="inline-flex">
                                <a v-tooltip.click="__('View')" target="_blank" :href="component.component_nova + '/' + component.id" class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center has-tooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current"><path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path></svg>
                                </a>
                            </span>
                            <span class="inline-flex">
                                <a v-tooltip.click="__('Edit')" target="_blank" :href="component.component_nova + '/' + component.id + '/edit'" class="inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>
                                </a>
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            active: {
                type: Boolean,
                default: false,
                required: true,
            },
            componentItem: {
                type: Object,
                required: true,
            },
            selected: {
                type: Array,
                default: () => [],
                required: false,
            },
            name: {
                type: String,
                default: () => '',
                required: false,
            }
        },

        computed: {
            isSelected() {
                return component => _.findIndex(this.selected, component) >= 0;
            }
        },

        methods: {
            select(component) {
                this.$emit('selectComponent', component);
            }
        },
    };
</script>

<style>
    .text-name {
        bottom: 0;
    }
</style>

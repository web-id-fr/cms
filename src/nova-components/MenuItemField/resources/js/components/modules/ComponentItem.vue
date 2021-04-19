<template>
    <div class="flex flex-wrap">
        <table class="table custom-table w-full table-auto">
            <thead>
            <tr>
                <th class="w-12">
                </th>
                <th class="text-left">
                    {{ __('Name') }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="menuItem in menuItems">
                <td v-on:click="select(menuItem)">
                    <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         class="block">
                        <g v-if="isSelected(menuItem)">
                            <rect width="20" height="20" rx="4" fill="var(--primary)"></rect>
                            <path fill="#FFF"
                                  d="M7.7 9.3c-.23477048-.3130273-.63054226-.46037132-1.01285927-.37708287-.38231702.08328846-.68093514.38190658-.7642236.7642236C5.83962868 10.0694577 5.9869727 10.4652295 6.3 10.7l2 2c.38884351.3811429 1.01115649.3811429 1.4 0l4-4c.3130273-.23477048.4603713-.63054226.3770829-1.01285927-.0832885-.38231702-.3819066-.68093514-.7642236-.7642236C12.9305423 6.83962868 12.5347705 6.9869727 12.3 7.3L9 10.58l-1.3-1.3v.02z"></path>
                        </g>
                        <g v-else>
                            <rect width="20" height="20" fill="#FFF" rx="4"></rect>
                            <rect width="19" height="19" fill="none" x=".5" y=".5" stroke="#CCD4DB" rx="4"></rect>
                        </g>
                    </svg>
                </td>
                <td v-on:click="select(menuItem)">
                    {{ getFirstTitle(menuItem.title) }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {selectFirstTitle} from "../../helpers";

    export default {
        props: {
            menuItems: {
                type: Object,
                required: true,
            },
            selected: {
                type: Array,
                default: () => [],
                required: false,
            },
        },

        data() {
            return {
                currentLocale: null,
            }
        },

        mounted() {
            this.currentLocale = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.currentLocale = lang;
            });
        },

        computed: {
            isSelected() {
                return menuItem => {
                    let selected = this.selected.findIndex(function (elem) {
                        return (_.isEqual(elem.title, menuItem.title) && _.isEqual(elem.menuable_type, menuItem.menuable_type));
                    });

                    if (selected >= 0) {
                        return true;
                    } else {
                        for (const [key, value] of Object.entries(this.selected)) {
                            if (_.findIndex(value.children, {
                                'title': menuItem.title,
                                'menuable_type': menuItem.menuable_type
                            }) >= 0) {
                                return true;
                            }
                        }

                        return false;
                    }
                }
            }
        },

        methods: {
            getFirstTitle(title) {
                return selectFirstTitle(title, this.currentLocale);
            },

            select(menuItem) {
                this.$emit('selectMenuItem', menuItem);
            },
        },
    };
</script>

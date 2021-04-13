<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="flex flex-col">
                <div class="flex flex-col mt-3">
                    <div class="justify-content-between row">
                        <div class="item-group" :key="el.id" v-for="el in field.value">
                            <div class="item shadow-md p-3 my-2">
                                {{ selectFirstTitle(el.title) }}
                            </div>
                            <div v-if="mapChildren(el)" class="item-container item-sub">
                                <div class="item-group" :key="submenu.id" v-for="submenu in mapChildren(el)">
                                    <div class="item shadow-md p-3 my-2">
                                        {{ selectFirstTitle(submenu.title) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </panel-item>
</template>

<script>
    import {mapChildren} from "../helpers";

    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],

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

        methods: {
            emitter(value) {
                this.$emit("input", value);
            },

            selectFirstTitle(title) {
                if(!title[this.currentLocale]) {
                    if(title[this.currentLocale + 1]) {
                        return title[this.currentLocale + 1];
                    } else if(title[this.currentLocale - 1]) {
                        return title[this.currentLocale -1];
                    } else {
                        return title[Object.keys(title)[0]];
                    }
                } else {
                    return title[this.currentLocale];
                }
            },

            mapChildren(el) {
                return mapChildren(el);
            }
        },
    }
</script>

<style>
    .item-container {
        margin: 0;
    }
    .item {
        padding: 1rem;
        margin: 5px;
    }
    .item-sub {
        margin: 0 0 0 3rem;
    }
</style>

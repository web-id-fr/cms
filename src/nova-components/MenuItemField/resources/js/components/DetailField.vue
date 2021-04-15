<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="flex flex-col">
                <div class="item-group" :key="el.id" v-for="el in field.value">
                    <div class="item">
                        <div class="item-title flex min-h-full w-full border border-60 rounded">
                            <span>
                                {{ selectFirstTitle(el.title) }}
                            </span>
                        </div>
                    </div>
                    <div v-if="mapChildren(el)" class="item-container item-sub">
                        <div class="item-group" :key="submenu.id" v-for="submenu in mapChildren(el)">
                            <div class="item">
                                <div class="item-title flex min-h-full w-full border border-60 rounded">
                                    <span>
                                        {{ selectFirstTitle(submenu.title) }}
                                    </span>
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
                if (!title[this.currentLocale]) {
                    if (title[this.currentLocale + 1]) {
                        return title[this.currentLocale + 1];
                    } else if (title[this.currentLocale - 1]) {
                        return title[this.currentLocale - 1];
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

<style scoped>
    .item-container {
        margin: 0;
    }
    .item {
        display: flex;
        margin-bottom: 5px;
    }
    span {
        padding: 1rem;
    }
    .item-sub {
        margin: 0 0 0 3rem;
    }
</style>

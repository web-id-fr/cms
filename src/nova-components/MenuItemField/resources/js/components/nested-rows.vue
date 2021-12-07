<template>
    <draggable
        v-bind="dragOptions"
        tag="div"
        class="item-container"
        :list="list"
        :value="value"
        @input="emitter"
    >
        <div class="item-group" :key="el.id" v-for="el in realValue">
            <div class="item">
                <div class="item-title flex min-h-full w-full border-t border-l border-b border-60 rounded-l">
                    <span>
                        <font-awesome-icon class="icons" icon="bars"/>
                        {{ getTitleAndSlug(el) }} ({{ __(getMenuItemType(el))}})
                    </span>
                </div>
                <div class="item-button z-10 bg-white border-t border-60 border rounded-r-lg h-auto pin-l pin-t self-start w-8">
                    <div>
                        <button v-tooltip.click="__('Detach')" @click.prevent="selectMenuItem(el)"
                                class="group-control btn w-8 h-8 block has-tooltip">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20"
                                 aria-labelledby="delete" role="presentation" class="fill-current">
                                <path fill="grey" fill-rule="nonzero"
                                      d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <nested-rows v-on:selectMenuItems="selectMenuItem" class="item-sub" :list="mapChildren(el)"/>
        </div>
    </draggable>
</template>

<script>
    import draggable from "vuedraggable";
    import {getMenuItemType, getTranslatedValue, mapChildren} from "../helpers";
    import {library} from '@fortawesome/fontawesome-svg-core';
    import {faBars} from '@fortawesome/free-solid-svg-icons';
    import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

    library.add(faBars);

    export default {
        name: "nested-rows",

        components: {
            draggable,
            'font-awesome-icon': FontAwesomeIcon,
        },

        data() {
            return {
                currentLocale: null,
            }
        },

        props: {
            value: {
                required: false,
                type: Array,
                default: null
            },
            list: {
                required: false,
                type: Array,
                default: null
            },
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

            getTitleAndSlug(menuItem) {
                let label = getTranslatedValue(menuItem.title, this.currentLocale);

                if (menuItem.slug !== undefined) {
                    label = label + " - " + getTranslatedValue(menuItem.slug, this.currentLocale);
                }

                return label;
            },

            mapChildren(el) {
                return mapChildren(el);
            },

            selectMenuItem(menuItem) {
                this.$emit('selectMenuItems', menuItem);
            },

            getMenuItemType(menuItem) {
                return this.__(getMenuItemType(menuItem));
            },
        },

        computed: {
            dragOptions() {
                return {
                    animation: 0,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            },
            // this.value when input = v-model
            // this.list  when input != v-model
            realValue() {
                return this.value ? this.value : this.list;
            }
        },
    };
</script>

<style scoped>
    .item-container {
        margin: 0;
    }
    .item {
        display: flex;
        margin-bottom: 5px;
    }
    .item-title {
        align-items: center;
    }
    span {
        padding: 1rem;
    }
    .item-button div {
        padding: 1rem 0;
    }
    .item-sub {
        margin: 0 0 0 3rem;
    }
    .icons {
        margin-right: 20px;
    }
</style>

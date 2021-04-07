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
            <div class="item shadow-md p-3 my-2"><font-awesome-icon class="icons" icon="bars"/>
              {{ selectFirstTitle(el.title) }}
            </div>
            <nested-rows class="item-sub" :list="mapChildren(el)" />
        </div>
    </draggable>
</template>

<script>
    import draggable from "vuedraggable";
    import {mapChildren} from "../helpers";
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
            }
        }
    };
</script>

<style scoped>
    .item-container {
        margin: 0;
    }
    .item {
        padding: 1rem;
        margin: 5px;
    }
    .item-sub {
        margin: 0 0 0 1rem;
    }
    .icons {
        margin-right: 20px;
    }
</style>

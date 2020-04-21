<template>
    <div>
        <div class="flex border-b border-40">
            <div class="w-1/5 py-6 px-8">
                <label class="inline-block text-80 pt-2 leading-tight">{{ label }}</label>
            </div>
            <div class="w-1/2 py-6 px-8">
                <select
                        v-model="menu_selected"
                        class="w-full form-control form-select"
                        @change="updateSelectedMenu"
                >
                    <option value="" selected>{{ __('Choose an option') }}</option>

                    <option
                            v-for="menu in menus"
                            :value="menu.id"
                            :selected="menu.id === menu_selected"
                    >
                        {{ menu.title }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['menus', 'value', 'label'],

        mounted() {
            this.currentLocale = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.changeTab(lang);
            });
        },

        data: () => ({
            menu_selected: null,
            currentLocale: '',
        }),

        methods: {
            updateSelectedMenu() {
                this.$emit('updateSelectedMenu', this.menu_selected);
            },
            changeTab(locale) {
                this.currentLocale = locale;
            },
        },

        watch: {
            'value': function (newValue) {
                this.menu_selected = newValue;
            }
        }
    }
</script>

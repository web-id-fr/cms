<template>
    <panel-item :field="field">
        <template slot="value">
            <span v-for="(item, key) in field.value">
                {{ selectFirstTitle(item.name) }}
                <span v-if="key !== Object.keys(field.value).length - 1">, </span>
            </span>
        </template>
    </panel-item>
</template>

<script>
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
            }
        },
    }
</script>

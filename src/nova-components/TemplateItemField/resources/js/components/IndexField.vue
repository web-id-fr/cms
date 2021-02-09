<template>
    <div>
        <span v-for="(item, key) in field.value">
            {{ selectFirstTitle(item.title) }}
            <span v-if="key !== Object.keys(field.value).length - 1">, </span>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['resourceName', 'field'],

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

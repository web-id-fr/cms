<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="w-1/2 shadow-md p-3 my-2"
                 v-for="item in field.value"
                 :key="item.id"
            >
                {{ selectFirstTitle(item.title, item.formable_type) }}
            </div>
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
            selectFirstTitle(title, formable_type) {
                let type;

                if(formable_type === 'Webid\\Cms\\App\\Models\\Modules\\Form\\TitleField'){
                    type = 'Title  | '
                } else {
                    type = 'Field | '
                }
                if(typeof title === 'string'){
                    return type + title;
                }
                if (!title[this.currentLocale]) {
                    if (title[this.currentLocale + 1]) {
                        return type + title[this.currentLocale + 1];
                    } else if (title[this.currentLocale - 1]) {
                        return type +title[this.currentLocale - 1];
                    } else {
                        return type + title[Object.keys(title)[0]];
                    }
                } else {
                    return type + title[this.currentLocale];
                }
            }
        },
    }
</script>

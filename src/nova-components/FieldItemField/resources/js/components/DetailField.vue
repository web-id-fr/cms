<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="relative flex bg-white mb-2 pb-1"
                 v-for="item in field.value"
                 :key="item.id"
            >
                <div class="z-10 bg-white border-t border-l border-b border-60 h-auto pin-l pin-t rounded-l self-start w-8">
                    <div>
                        <button disabled type="button" class="group-control btn border-t border-r border-40 w-8 h-8 bloc">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 20 20" stroke="grey">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                        </button>
                        <button disabled type="button" class="group-control btn border-t border-r border-40 w-8 h-8 block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 20 20" stroke="grey">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <button disabled  class="group-control btn border-t border-r border-40 w-8 h-8 block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                                <path fill="grey" fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex min-h-full w-full border-60 border rounded-r-lg">
                    <div class="flex-auto self-center text-center py-2 m-2">
                        <p class="break-words">{{ selectFirstTitle(item.title, item.formable_type) }}</p>
                    </div>
                </div>
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

                if(formable_type === 'Webid\\Cms\\Modules\\Form\\Models\\TitleField'){
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

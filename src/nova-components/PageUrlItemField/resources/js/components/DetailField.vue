<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="mt-4">
                <span v-if="value !== '—'" class="whitespace-no-wrap">
                    <a class="no-underline dim text-primary font-bold" :href="url" target="_blank">{{ url }}</a>
                </span>
                <span v-else class="whitespace-no-wrap">—</span>
            </div>
        </template>
    </panel-item>
</template>

<script>
    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],

        data() {
            return {
                currentLocale: Object.keys(this.field.locales)[0]
            }
        },

        mounted() {
            this.currentLocale = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.changeTab(lang);
            });
        },

        methods: {
            changeTab(locale) {
                this.currentLocale = locale
            }
        },

        computed: {
            value() {
                return this.field.value[this.currentLocale] || '—'
            },

            url() {
                return this.field.projectUrl + '/' + this.currentLocale + '/' + this.value
            }
        },
    }
</script>

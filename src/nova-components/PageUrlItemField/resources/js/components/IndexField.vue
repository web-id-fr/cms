<template>
    <span v-if="value !== '—'" class="whitespace-no-wrap">
        <a :href="url" target="_blank">{{ url }}</a>
    </span>
    <span v-else class="whitespace-no-wrap">—</span>
</template>

<script>
    export default {
        props: ['resourceName', 'field'],

        mounted() {
            this.language = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.language = lang;
            });
        },

        data() {
            return {
                language: null
            }
        },

        computed: {
            value() {
                return this.field.value[this.language] || '—'
            },

            url() {
                return this.field.projectUrl + '/' + this.language + '/'  + this.value
            }
        }
    }
</script>

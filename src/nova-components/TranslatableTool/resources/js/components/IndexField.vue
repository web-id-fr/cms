<template>
    <div v-if="field.shortenText">
        <div v-if="field.asHtml" v-html="value.substr(0, 50) + '...'"></div>
        <span v-else class="whitespace-no-wrap">{{ value.substr(0, 50) + '...' }}</span>
    </div>
    <div v-else>
        <div v-if="field.asHtml" v-html="value"></div>
        <span v-else class="whitespace-no-wrap">{{ value }}</span>
    </div>
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
            }
        }
    }
</script>

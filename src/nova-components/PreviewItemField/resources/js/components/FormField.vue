<template>
    <div class="pt-1 flex justify-end">
        <button type="button" class="btn btn-default btn-primary inline-flex items-center relative mr-3"
                @click="getAllFieldsValue">
                    <span class="">
                        {{ __("Preview in") + ' ' + this.currentLocale }}
                    </span>
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            resourceName: {
                type: String,
                require: true,
            },
            field: {
                type: Object,
                require: true,
            },
        },

        data() {
            return {
                fields: {},
                currentLocale: null,
            }
        },

        created() {
            this.field.fill = () => {}
        },

        mounted() {
            this.currentLocale = document.querySelector('#select-language-translatable').value;
            Nova.$on('change-language', (lang) => {
                this.currentLocale = lang;
            });
        },

        methods: {
            getAllFieldsValue() {
                this.$parent.$children.forEach(component => {
                    if (component.field !== undefined &&
                        component.field.value !== null &&
                        (component.field.attribute !== "" || component.field.attribute !== this.field.attribute)
                    ) {
                        if (typeof component.field.value === 'object') {
                            if (component.field.attribute === 'components') {
                                this.fields[component.field.attribute] = component.selected;
                            } else {
                                this.fields[component.field.attribute] = component.field.value[this.currentLocale];
                            }
                        } else {
                            this.fields[component.field.attribute] = component.field.value;
                        }
                    }
                });

                this.fields['lang'] = this.currentLocale;

                Nova.request().post('/nova-vendor/preview-item-field/store-preview-data',
                    this.fields
                ).then(({data}) => {
                    window.open("/preview/" + data.token, '_blank');
                    this.$toasted.show(
                        this.__('The preview has been correctly created.'),
                        {type: 'success'}
                    )
                }).catch(() => {
                    this.$toasted.show(
                        this.__('An unexpected error occurred during the creation of the preview.'),
                        {type: 'error'}
                    )
                });
            }
        }
    }
</script>

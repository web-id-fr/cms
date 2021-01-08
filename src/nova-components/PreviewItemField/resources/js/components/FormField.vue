<template>
    <div class="pt-1 flex justify-end">
        <button type="button" class="btn btn-default btn-primary inline-flex items-center relative mr-3"
                @click="getAllFieldsValue">
                    <span class="">
                        {{ __("Preview") }}
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

        created() {
            this.field.fill = () => {}
        },

        methods: {
            getAllFieldsValue() {
                let value = [];

                this.$parent.$children.forEach(component => {
                    if (component.field !== undefined && component.field.attribute !== "") {
                        value[component.field.attribute] = component.field.value;

                        if (component.field.attribute === 'components') {
                            value[component.field.attribute] = component.selected;
                        }
                    }
                });

                return value
            }
        }
    }
</script>

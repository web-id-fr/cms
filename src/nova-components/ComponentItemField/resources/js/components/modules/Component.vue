<template>
    <transition>
        <template>
            <div @click="showChildComponent"
                 ref="card"
                 class="w-1/6 card relative flex flex-wrap border border-lg border-50 cursor-pointer m-2">

                <template>
                    <img :src="component[0]['component_image']" class="image-component" :alt="name">
                    <div class="h-1/6 w-full text-center text-xs border-t border-30 bg-50 flex p-1 items-center justify-center absolute text-name">
                        {{ __(name) }}
                    </div>
                </template>
            </div>
        </template>
    </transition>
</template>

<script>
    export default {
        props: {
            component: {
                type: Object,
                required: true,
            },
            name: {
                type: String,
                default: function() {
                    return { name: '' };
                },
                required: true,
            }
        },

        methods: {
            showChildComponent() {
                this.$emit('showComponentItems', this.component, this.name);
            },
        },
    };
</script>

<style lang="scss" scoped>
    .image-component {
        max-width: 100%;
        height: auto;
        object-fit: contain;
        min-height: 8rem;
        border-radius: 0.375rem;
    }
    .text-name {
        border-bottom-right-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        bottom: 0;
    }
</style>

<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-80 font-light">Card Actions</h1>
        </div>
        <div class="mb-2">
            <button class="btn btn-default btn-danger" @click="flushVarnish" :disabled="flush_varnish_loading">Varnish Flush</button>
        </div>
    </card>
</template>

<script>
    import {flushVarnish} from "../api";

    export default {
        props: [
            'card',
        ],

        data: () => ({
            flush_varnish_loading: false,
        }),

        methods: {
            flushVarnish: function() {
                this.flush_varnish_loading = true;
                flushVarnish().then((response) => {
                    this.$toasted.show('Varnish flushed !', { type: 'success' });
                    this.flush_varnish_loading = false;
                }).catch((error) => {
                    this.$toasted.show('Error during the flush.', { type: 'error' });
                    this.flush_varnish_loading = false;
                });
            }
        },
    }
</script>

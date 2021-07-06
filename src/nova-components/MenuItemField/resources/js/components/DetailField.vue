<template>
    <panel-item :field="field">
        <template slot="value">
            <detail-tree
                    class="col-8"
                    v-model="field.value"
            />
        </template>
    </panel-item>
</template>

<script>
    import DetailTree from "./detail-tree";

    export default {
        components: {DetailTree},
        props: ['resource', 'resourceName', 'resourceId', 'field'],
    }
</script>

<style scoped>
    span {
        padding: 1rem;
    }
</style>

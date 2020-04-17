<template>
    <panel-item :field="field" />
</template>

<script>
    export default {
        props: ['resourceName', 'resourceId', 'field'],
    }
</script>

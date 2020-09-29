<template>
    <panel-item :field="field">
        <template slot="value">
            <span v-for="(item, key) in field.value">
                {{ item.name }}
                <span v-if="key !== Object.keys(field.value).length - 1">, </span>
            </span>
        </template>
    </panel-item>
</template>

<script>
    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],
    }
</script>

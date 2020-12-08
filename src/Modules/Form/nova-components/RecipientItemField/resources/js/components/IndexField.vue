<template>
    <div>
        <span v-for="(item, key) in field.value">
            {{ item.name }}
            <span v-if="key !== Object.keys(field.value).length - 1">, </span>
        </span>
    </div>
</template>
<script>
    export default {
        props: ['resourceName', 'field'],
    }
</script>

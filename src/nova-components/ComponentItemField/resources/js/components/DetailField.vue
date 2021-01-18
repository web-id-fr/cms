<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="w-2/3 shadow-md p-3 my-2 flex justify-between"
                 v-for="item in field.value"
                 :key="item.id"
            >
                <div class="flex-auto self-center text-center py-2 m-2">
                    <p class="break-words">{{ item.name }}</p>
                </div>
                <div class="flex-auto self-center">
                    <img class="m-auto block" width="200px" :src="item.component_image">
                </div>
                <div class="flex-auto self-center text-center py-2 m-2">
                    <a v-tooltip.click="__('Edit')" target="_blank" :href="item.component_nova + '/' + item.id + '/edit'"
                       class="inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current">
                            <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </template>
    </panel-item>
</template>

<script>
    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],
    }
</script>

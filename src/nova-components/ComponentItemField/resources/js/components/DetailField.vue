<template>
    <panel-item :field="field">
        <template slot="value">
            <div class="w-1/2 shadow-md p-3 my-2 flex justify-between"
                 v-for="item in field.value"
                 :key="item.id"
            >
                <div class="flex-auto self-center text-center py-2 m-2">
                    <p class="break-words">{{ item.name }}</p>
                </div>
                <div class="flex-auto">
                    <img class="m-auto block" width="200px" :src="item.component_image">
                </div>
                <div class="flex-auto self-center text-center py-2 m-2">
                    <a target="_blank" :href="item.component_nova + '/' + item.id + '/edit'">
                        <font-awesome-icon icon="pen"/>
                    </a>
                </div>
            </div>
        </template>
    </panel-item>
</template>

<script>
    import {library} from '@fortawesome/fontawesome-svg-core';
    import {faPen} from '@fortawesome/free-solid-svg-icons';
    import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

    library.add(faPen);

    export default {
        props: ['resource', 'resourceName', 'resourceId', 'field'],

        components: {
            'font-awesome-icon': FontAwesomeIcon,
        },
    }
</script>

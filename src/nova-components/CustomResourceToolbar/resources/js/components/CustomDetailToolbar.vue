<template>
  <div class="flex w-full justify-end items-center mx-3">
    <component v-if="hasComponent" :is="component" />
  </div>
</template>

<script>
export default {
  // props: ['resourceName', 'resourceId'],
  props: ['resourceName'],
  computed: {
    component(){
      return this.resourceName + '-create-header';
    },
    hasComponent()
    {
      return this.component in this.$options.components;
    }
  }
}
</script>

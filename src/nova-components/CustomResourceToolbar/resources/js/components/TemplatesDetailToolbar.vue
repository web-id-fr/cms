<template>
  <div>
    <div class="flex w-full justify-end items-center">
      <a
          class="btn btn-default btn-icon btn-white"
          :href="'/nova-vendor/custom-resource-toolbar/export-quote/' + this.$parent.resourceId">
        Export as PDF
      </a>
      <span class="btn btn-default btn-icon btn-white" @click="zozo()">Preview</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'field'],
  mounted() {
    var elements = document.getElementById('nova').querySelectorAll('h4');
    [].forEach.call(elements, function(element) {
      if(element.innerHTML === 'Custom Detail Toolbar') {
        // element.parentNode.remove();
        console.log(element);
      }
    });
  },
  methods: {
    zozo() {
      console.log("coucou", this.resource, this.resourceName, this.field)
    }
  }
}
</script>

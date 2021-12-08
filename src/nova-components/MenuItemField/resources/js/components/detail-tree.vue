<template>
  <div>
    <div class="item-group" :key="el.id" v-for="el in realValue">
      <div class="item">
        <div class="item-title flex min-h-full w-full border-t border-l border-b border-60 rounded-l">
          <span>{{ getTitleAndSlug(el) }} ({{ __(getMenuItemType(el)) }})</span>
        </div>
      </div>

      <detail-tree class="item-sub" :list="mapChildren(el)"/>
    </div>
  </div>
</template>

<script>
import {getMenuItemType, getTranslatedValue, mapChildren} from "../helpers";

export default {
  name: "detail-tree",

  data() {
    return {
      currentLocale: null,
    }
  },

  props: {
    value: {
      required: false,
      type: Array,
      default: null
    },
    list: {
      required: false,
      type: Array,
      default: null
    },
  },

  mounted() {
    this.currentLocale = document.querySelector('#select-language-translatable').value;
    Nova.$on('change-language', (lang) => {
      this.currentLocale = lang;
    });
  },

  methods: {
    getTitleAndSlug(menuItem) {
      let label = getTranslatedValue(menuItem.title, this.currentLocale);

      if (menuItem.slug !== undefined) {
        label = label + " - " + getTranslatedValue(menuItem.slug, this.currentLocale);
      }

      return label;
    },

    mapChildren(el) {
      return mapChildren(el);
    },

    getMenuItemType(menuItem) {
      return this.__(getMenuItemType(menuItem));
    },
  },

  computed: {
    // this.value when input = v-model
    // this.list  when input != v-model
    realValue() {
      return this.value ? this.value : this.list;
    }
  },
};
</script>

<style scoped>
.item {
  display: flex;
  margin-bottom: 5px;
}

span {
  padding: 1rem;
}

.item-sub {
  margin: 0 0 0 3rem;
}
</style>

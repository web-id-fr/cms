<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="true" class="advanced-url-field-wrapper">
    <template slot="field">
      <search-input
          v-if="!isReadonly"
          @input="performSearch"
          @clear="clearSelection"
          @selected="selectResource"
          :error="hasError"
          :value="selectedResource[currentLocale]"
          :data="availableResources"
          :clearable="field.nullable"
          trackBy="value"
          class="w-full"
      >
        <slot name="default" v-if="aValueIsSelectedForCurrentLocale" class="flex items-center">
          {{ currentSelectedLabel }}
        </slot>

        <div
            slot="option"
            slot-scope="{ option, selected }"
            class="flex items-center"
        >
          <div>
            <div class="text-sm font-semibold leading-5 text-90" :class="{ 'text-white': selected }">
              {{ option.display }}
            </div>
            <div
                class="mt-1 text-xs font-semibold leading-5 text-80"
                :class="{ 'text-white': selected }"
            >
              <span v-if="option.subtitle">{{ option.subtitle }}</span>
            </div>
          </div>
        </div>
      </search-input>

      <select-control
          v-if="isReadonly"
          class="form-control form-select w-full"
          :class="{ 'border-danger': hasError }"
          :data-testid="`${field.resourceName}-select`"
          :dusk="field.attribute"
          :disabled="true"
          label="display"
          :options="[]"
      >
        <option value="" selected :disabled="true">
          {{ currentSelectedLabel }}
        </option>
      </select-control>
    </template>
  </default-field>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova';
import _ from "lodash";

export default {
  mixins: [
    FormField,
    HandlesValidationErrors,
  ],

  data: () => ({
    availableResources: [],
    selectedResource: [],
    locales: [],
    currentLocale: null,
  }),

  props: ['resourceName', 'resourceId', 'field'],

  mounted() {
    this.locales = Object.keys(this.field.locales);
    this.currentLocale = document.querySelector('#select-language-translatable').value;

    Nova.$on('change-language', (lang) => {
      this.currentLocale = lang;
    });
  },

  methods: {
    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || [];

      for (let key of Object.keys(this.value)) {
        this.$set(
            this.selectedResource,
            key,
            {
              value: this.value[key]
            }
        )
      }

      this.$forceUpdate();
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      Object.keys(this.value).forEach(locale => {
        formData.append(this.field.attribute + '[' + locale + ']', this.value[locale] || '')
      })
    },

    performSearch(search) {
      this.availableResources = [{
        value: search,
        display: search
      }];

      Nova.request().get(`/nova-api/templates/`, {
        params: {
          search: search,
          perPage: 25,
          page: 1,
        }
      }).then((function (response) {
        const foundPages = response.data.resources;

        for (let page of foundPages) {
          for (let localeKey in page.urls) {
            this.availableResources.push({
              value: page.urls[localeKey],
              display: page.titles[localeKey],
              subtitle: page.urls[localeKey],
            })
          }
        }
      }).bind(this))
    },

    selectResource(selectedResource) {
      this.$set(this.selectedResource, this.currentLocale, selectedResource);
      this.$set(this.value, this.currentLocale, selectedResource.value);

      this.$forceUpdate();
    },

    clearSelection() {
      this.$set(this.selectedResource, this.currentLocale, null);
      this.$set(this.value, this.currentLocale, null);

      this.$forceUpdate();
    }
  },

  computed: {
    aValueIsSelectedForCurrentLocale() {
      return typeof this.selectedResource[this.currentLocale] !== "undefined" && this.selectedResource[this.currentLocale] !== null;
    },

    currentSelectedLabel() {
      if (this.aValueIsSelectedForCurrentLocale) {
        let selectedResourceForCurrentLocale = this.selectedResource[this.currentLocale];

        if (selectedResourceForCurrentLocale.display) {
          return selectedResourceForCurrentLocale.display;
        }

        return selectedResourceForCurrentLocale.value;
      }

      return '';
    },

    isReadonly() {
      return (
          this.field.readonly || _.get(this.field, 'extraAttributes.readonly')
      )
    },

    /**
     * Return the placeholder text for the field.
     */
    placeholder() {
      return this.field.placeholder || this.__('Type an URL or search for a page')
    },
  }
}
</script>

<style>
.advanced-url-field-wrapper .relative > .flex {
  height: 100%;
  min-height: 36px;
}
</style>

<template>
  <default-field :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="true" class="advanced-url-field-wrapper">
    <slot name="form-label">
      <form-label :for="field.name">
        <font-awesome-icon icon="flag"/>
        {{ field.name }}
        <span v-if="field.required" class="text-danger text-sm">*</span>
      </form-label>
    </slot>
    <template slot="field">
      <search-input
          v-if="!isReadonly"
          :data-testid="`${field.resourceName}-search-input`"
          ref="field"
          :error="hasError"
          @input="performSearch"
          @selected="selectResource"
          @clear="clearSelection"
          :data="availableResources"
          :clearable="field.nullable"
          :debounce="field.debounce"
          :value="selectedResource[currentLocale]"
          trackBy="value"
          class="w-full"
      >
        <slot name="default" v-if="aValueIsSelectedForCurrentLocale" class="flex items-center">
          {{ currentSelectedLabel }}
        </slot>
        <slot name="default" v-else>
          <div class="text-70">{{ __('Type an URL or search for a page') }}</div>
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
import {library} from '@fortawesome/fontawesome-svg-core';
import {faFlag} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import _ from "lodash";

library.add(faFlag);

export default {
  mixins: [
    FormField,
    HandlesValidationErrors,
  ],

  components: {
    'font-awesome-icon': FontAwesomeIcon,
  },

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

  }
}
</script>

<style>
.advanced-url-field-wrapper .relative > .flex {
  height: 100%;
  min-height: 36px;
}
</style>

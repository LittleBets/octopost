<template>
  <input
    v-model='proxyChecked'
    :value='value'
    class='rounded border-gray-300 text-gray-600 shadow-sm focus:border-gray-300 focus:ring focus:ring-gray-200 focus:ring-opacity-50'
    type='checkbox'
  />
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    checked: {
      type: [Array, Boolean],
      default: false,
    },
    value: {
      default: null,
    },
  },
  emits: ['update:checked'],

  computed: {
    proxyChecked: {
      get() {
        return this.checked
      },

      set(val) {
        this.$emit('update:checked', val)
      },
    },
  },
})
</script>

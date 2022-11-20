<template>
  <div class="isolate rounded-md shadow-sm">
    <div
      class="group relative rounded border border-gray-300 px-4 py-1.5 focus-within:z-10 focus-within:border-gray-600 focus-within:ring-1 focus-within:ring-gray-600"
    >
      <div class="text-gray-500 group-focus-within:text-gray-700">
        <slot name="label">
          <RequiredFieldLabel v-if="label" :label="label" :required="required" class="pb-2" />
        </slot>
      </div>
      <textarea
        ref="input"
        v-bind="$attrs"
        :value="modelValue"
        :label="label"
        :name="name ?? label"
        :required="required"
        class="block w-full border-0 bg-transparent p-0 text-gray-900 placeholder-gray-500 focus:ring-0"
        :placeholder="affixPlaceholder ? '' : placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
      />
      <div
        v-if="$slots.trail"
        class="pointer-events-none absolute inset-y-0 right-0 flex items-end pr-3 pb-2"
      >
        <span class="text-gray-500 sm:text-sm"> <slot name="trail" /> </span>
      </div>
      <div v-if="placeholder && affixPlaceholder" class="py-2 text-sm text-gray-500">
        <span>
          {{ placeholder }}
        </span>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import RequiredFieldLabel from '@/Components/RequiredFieldLabel.vue'

defineProps({
  modelValue: { type: [String, Number], required: true },
  label: { type: String, default: undefined },
  name: { type: String, default: undefined },
  required: { type: Boolean, default: false },
  placeholder: { type: String, default: undefined },
  affixPlaceholder: { type: Boolean, default: false },
})
defineEmits(['update:modelValue'])

defineExpose({
  focus() {
    doFocus()
  },
})

const input = ref<HTMLElement>()

function doFocus() {
  input.value?.focus()
}
</script>

<template>
  <TextInput
    v-model.number="value"
    label="Number of Variations"
    :max="5"
    :min="1"
    required
    type="number"
    tooltip="Total number of results to return. Each variation will be a different response to the same message. Each additional variation will use extra word credits."
  />
</template>

<script setup lang="ts">
import TextInput from '@/Components/TextInput.vue'
import { computed } from 'vue'

const props = defineProps({
  modelValue: { type: Number, default: 1 },
})
const emit = defineEmits(['update:modelValue', 'update:checked'])

const value = computed({
  get() {
    return props.modelValue
  },
  set(val) {
    emit('update:modelValue', val)
  },
})
</script>

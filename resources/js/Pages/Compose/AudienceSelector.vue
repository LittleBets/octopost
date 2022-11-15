<template>
  <ComboItemSelector
    v-model="selected"
    v-model:checked="checkState"
    :options="availableAudiences"
    :title="title"
    with-checkbox
  />
</template>

<script lang="ts" setup>
import { audiences } from '@/Pages/Compose/templates'
import ComboItemSelector from '@/Pages/Compose/ComboItemSelector.vue'

const { title = 'Target Audience', modelValue = null, checked = false } = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'update:checked'])
const availableAudiences = $computed(() => [...audiences, { id: modelValue, title: modelValue }])

const selected = $computed({
  get() {
    return modelValue
  },
  set(val) {
    emit('update:modelValue', val)
  },
})

const checkState = $computed({
  get: () => checked,
  set: (val) => {
    emit('update:checked', val)
  },
})

interface Props {
  title?: string
  modelValue?: string
  checked?: boolean
}
</script>

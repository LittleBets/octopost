<template>
  <ListItemSelector
    v-model="selected"
    v-model:checked="checkState"
    :options="rewriteTypes"
    :title="title"
    :with-checkbox="withCheckbox"
    tooltip="You can expand, compact, correct grammatical issues of your text, or just rephrase it in a different style."
  />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { rewriteTypes } from '@/Pages/Compose/templates'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'
interface Props {
  title?: string
  modelValue: string
  checked?: boolean
  withCheckbox?: boolean
}

const {
  title = 'Action',
  modelValue = 'rewrite',
  checked = false,
  withCheckbox = false,
} = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'update:checked'])
const checkState = $computed({
  get: () => checked,
  set: (val) => {
    emit('update:checked', val)
  },
})
const selected = computed({
  get() {
    return modelValue
  },
  set(val) {
    emit('update:modelValue', val)
  },
})
</script>

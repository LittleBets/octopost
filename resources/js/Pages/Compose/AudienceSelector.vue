<template>
  <ListItemSelector
    v-model="selected"
    v-model:checked="checkState"
    :options="audiences"
    :title="title"
    with-checkbox
  />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { audiences } from '@/Pages/Compose/Templates/templates'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'

const { title = 'Target Audience', modelValue = null, checked = true } = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'update:checked'])

const selected = computed({
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

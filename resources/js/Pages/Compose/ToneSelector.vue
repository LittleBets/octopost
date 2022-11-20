<template>
  <ComboItemSelector
    v-model="selected"
    v-model:checked="checkState"
    :options="availableTones"
    :title="title"
    with-checkbox
    tooltip="Tone of the output. Keep it unchecked for a neutral tone or type-in a custom one (and hit enter)."
  />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { tones } from '@/Pages/Compose/templates'
import ComboItemSelector from '@/Pages/Compose/ComboItemSelector.vue'

const { title = 'Tone', modelValue = null, checked = false } = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'update:checked'])
const availableTones = $computed(() => [...tones, { id: modelValue, title: modelValue }])
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

interface Props {
  title?: string
  modelValue?: string
  checked?: boolean
}
</script>

<template>
  <RadioGroupSelector v-model="selected" :options="compositionLengths" :label="title" />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { compositionLengths } from '@/Pages/Compose/Templates/templates'
import RadioGroupSelector from '@/Components/RadioGroupSelector.vue'

interface Props {
  title?: string
  modelValue: string
}

const { title = 'Composition Length', modelValue = 'short' } = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const selected = computed({
  get() {
    return modelValue
  },
  set(val) {
    emit('update:modelValue', val)
  },
})
</script>

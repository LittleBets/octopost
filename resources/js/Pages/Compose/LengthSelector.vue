<template>
  <ListItemSelector v-model="selected" :options="compositionLengths" :title="title" />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { compositionLengths } from '@/Pages/Compose/templates'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'
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

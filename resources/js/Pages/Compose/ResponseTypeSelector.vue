<template>
  <ListItemSelector v-model="selected" :options="responseTypes" :title="title" />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { responseTypes } from '@/Pages/Compose/templates'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'

interface Props {
  title?: string
  modelValue?: string
}

const { title = 'Responding To', modelValue = null } = defineProps<Props>()
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

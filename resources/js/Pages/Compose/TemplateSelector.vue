<template>
  <ListItemSelector v-model="selected" :options="templates" :title="title" />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { templates } from '@/Pages/Compose/templates'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'

interface Props {
  title?: string
  modelValue?: string
}

const { title = 'Select a Composition Type', modelValue = null } = defineProps<Props>()
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

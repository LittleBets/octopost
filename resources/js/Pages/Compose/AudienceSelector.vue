<template>
  <ListItemSelector
    v-model='selected'
    :options='audiences'
    :title='title'
  />
</template>

<script lang='ts' setup>
import { computed } from 'vue'
import { audiences } from '@/Pages/Compose/Templates/templates'
import ListItemSelector from '@/Pages/Compose/ListItemSelector.vue'

interface Props {
  title?: string
  modelValue?: string
}

const { title = 'Select Target Audience', modelValue = null } = defineProps<Props>()
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

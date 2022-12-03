<template>
  <ResultHeader :title="`Text to ${ucFirst(composition.payload.rewrite_type ?? 'rewrite')}`">
    <p>
      {{ composition.payload.text }}
    </p>
  </ResultHeader>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import ResultHeader from '@/Pages/Composition/ResultHeader.vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<RewriteCompositionPayload>>,
    required: true,
  },
})

function ucFirst(str: string) {
  return str.replace(/^./, (str) => str.toUpperCase())
}
</script>

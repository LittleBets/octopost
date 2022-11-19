<template>
  <ResultHeader title="Text to Summarize">
    {{ composition.payload.text }}
  </ResultHeader>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import ResultHeader from '@/Pages/Composition/ResultHeader.vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<SummaryCompositionPayload>>,
    required: true,
  },
})
</script>

<template>
  <ResultHeader title="Message to Reply">
    {{ composition.payload.message }}
  </ResultHeader>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import ResultHeader from '@/Pages/Composition/ResultHeader.vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<ResponseCompositionPayload>>,
    required: true,
  },
})
</script>

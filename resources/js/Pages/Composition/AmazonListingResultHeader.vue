<template>
  <ResultHeader title="Product Features">
    {{ composition.payload.features }}
  </ResultHeader>
</template>

<script setup lang="ts">
import { PropType } from 'vue'
import ResultHeader from '@/Pages/Composition/ResultHeader.vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<AmazonListingCompositionPayload>>,
    required: true,
  },
})
</script>

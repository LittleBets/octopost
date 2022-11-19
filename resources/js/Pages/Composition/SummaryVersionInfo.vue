<template>
  <div>
    A
    <span class="my-1 py-0.5 font-semibold text-gray-800">{{
      composition.payload.composition_length
    }}</span>
    <span> summary of a piece of text</span>
    <template v-if="composition.payload.audience">
      addressing
      <span class="my-1 py-0.5 font-semibold text-gray-800">{{
        composition.payload.audience
      }}</span> </template
    >.
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: { type: Object as PropType<Composition<SummaryCompositionPayload>>, required: true },
})
</script>

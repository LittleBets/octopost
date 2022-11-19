<template>
  <div>About {{ composition.payload.composition_length }} word long freeform prompt.</div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<FreeformCompositionPayload>>,
    required: true,
  },
})
</script>

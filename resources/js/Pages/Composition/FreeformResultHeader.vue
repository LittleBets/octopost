<template>
  <div class="rounded p-4 shadow">
    <h3 class="text-sm text-gray-500">Prompt</h3>
    <p class="pt-2 font-medium leading-8">
      {{ composition.payload.input_prompt }}
    </p>
  </div>
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

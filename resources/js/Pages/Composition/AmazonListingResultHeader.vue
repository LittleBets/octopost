<template>
  <div class="rounded p-4 shadow">
    <h3 class="text-sm text-gray-500">Product Features</h3>
    <p class="pt-2 font-medium leading-8">
      {{ composition.payload.features }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<AmazonListingCompositionPayload>>,
    required: true,
  },
})
</script>

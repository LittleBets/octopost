<template>
  <div>
    A
    <span class="my-1 py-0.5 font-medium text-gray-800">{{
      composition.payload.composition_length
    }}</span>
    listing for
    <span class="my-1 py-0.5 font-medium text-gray-800">{{ composition.payload.name }}</span>
    targeting
    <span class="my-1 py-0.5 font-medium text-gray-800">{{
      composition.payload.audience ?? 'everyone'
    }}</span>

    <template v-if="composition.payload.tone">
      in a
      <span class="my-1 py-0.5 font-medium text-gray-800">{{ composition.payload.tone }}</span>
      tone</template
    >.
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

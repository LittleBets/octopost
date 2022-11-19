<template>
  <div>
    A
    <span class="my-1 py-0.5 font-semibold text-gray-800">{{
      composition.payload.composition_length
    }}</span
    ><span class="my-1 py-0.5 font-semibold text-gray-800"
      >&nbsp;{{ composition.payload.response_type }}
    </span>
    <span> response</span>
    <template v-if="composition.payload.audience">
      addressing
      <span class="my-1 py-0.5 font-semibold text-gray-800">{{
        composition.payload.audience
      }}</span>
    </template>
    <template v-if="composition.payload.tone">
      in a
      <span class="my-1 py-0.5 font-semibold text-gray-800">{{ composition.payload.tone }}</span>
      tone</template
    >.
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: {
    type: Object as PropType<Composition<ResponseCompositionPayload>>,
    required: true,
  },
})
</script>

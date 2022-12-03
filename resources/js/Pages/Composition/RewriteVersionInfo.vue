<template>
  <div>
    A
    <span class="my-1 py-0.5 font-semibold text-gray-800">{{
      composition.payload.composition_length
    }}</span>
    word<span class="my-1 py-0.5 font-semibold text-gray-800">&nbsp;{{ responseType }} </span>
    <template v-if="composition.payload.audience">
      of text addressing
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

const props = defineProps({
  composition: {
    type: Object as PropType<Composition<RewriteCompositionPayload>>,
    required: true,
  },
})

const responseType = $computed(() => {
  switch (props.composition.payload.rewrite_type) {
    case 'expand':
      return 'expansion'
    case 'condense':
      return 'compaction'
    case 'correct':
      return 'correction'
    default:
      return 'rewrite'
  }
})
</script>

<template>
  <div>
    A
    <span class="my-1 py-0.5 font-medium text-gray-800">{{
      composition.payload.composition_length
    }}</span>

    {{ composition.payload.response_type }}
    response
    <span class="my-1 py-0.5 font-medium text-gray-800">{{ composition.payload.name }}</span>
    <template v-if="composition.payload.audience">
      addressing
      <span class="my-1 py-0.5 font-medium text-gray-800">{{ composition.payload.audience }}</span>
    </template>
    in a
    <span class="my-1 py-0.5 font-medium text-gray-800">{{ composition.payload.tone }}</span>
    tone.
  </div>
</template>

<script setup lang="ts">
import { PropType } from 'vue'

defineProps({
  composition: { type: Object as PropType<Composition>, required: true },
})
</script>

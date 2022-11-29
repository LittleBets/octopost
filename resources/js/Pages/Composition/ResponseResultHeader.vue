<template>
  <ResultHeader title="Message to Reply">
    <div>
      <p>
        {{ composition.payload.message }}
      </p>
      <div v-if="composition.payload.points_to_include">
        <div class="mt-4 text-sm font-bold text-gray-600">Points to include:</div>
        {{ composition.payload.points_to_include }}
      </div>
    </div>
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

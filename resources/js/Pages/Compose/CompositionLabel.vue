<template>
  <div class="mx-8 pb-4 pl-4 text-xl font-medium text-gray-500">
    <slot name="label">
      {{ label ?? `&nbsp;` }}
    </slot>
    <CompositionLabelUpdateModal
      v-if="compositionId"
      v-model="label"
      :show="showingLabelDialog"
      :composition-id="compositionId"
      @close="showingLabelDialog = false"
    />
  </div>
</template>

<script setup lang="ts">
import CompositionLabelUpdateModal from '@/Pages/Compose/CompositionLabelUpdateModal.vue'
import { watch } from 'vue'

const props = defineProps({
  value: { type: String, default: undefined },
  compositionId: { type: String, default: undefined },
})

const showingLabelDialog = $ref(false)

let label = $ref<string | undefined>(props.value)

watch(
  () => props.value,
  (value) => {
    label = value
  }
)
</script>

<template>
  <div ref="containerRef" class="ml-10 pb-4 text-xl font-medium text-gray-500">
    <slot name="label">
      <div v-if="label" class="flex items-center gap-4">
        {{ label }}
        <LinkButton v-tippy="'Edit Label &nbsp;&nbsp;&nbsp;E'" @click="showingLabelDialog = true">
          <icon icon="ph:pencil-simple" class="h-5 w-auto group-hover:text-red-500" />
        </LinkButton>
      </div>
      <span v-else>&nbsp;</span>
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
import { ref, watch } from 'vue'
import LinkButton from '@/Components/LinkButton.vue'
import { useMagicKeys, whenever } from '@vueuse/core'
import { and } from '@vueuse/math'
import { useIsMouseInside } from '@/compositions/useIsMouseInside'

const props = defineProps({
  value: { type: String, default: undefined },
  compositionId: { type: String, default: undefined },
})

let showingLabelDialog = $ref(false)

let label = $ref<string | undefined>(props.value)

watch(
  () => props.value,
  (value) => {
    label = value
  }
)

const containerRef = ref<HTMLElement | null>(null)
const { isInside } = useIsMouseInside(containerRef)
const { e } = useMagicKeys()
whenever(and(e, isInside), () => {
  showingLabelDialog = true
})
</script>

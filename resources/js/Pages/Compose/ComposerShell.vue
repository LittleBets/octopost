<template>
  <div class="flex w-full flex-1 flex-col overflow-y-hidden">
    <CompositionLabel :value="compositionLabel" :composition-id="rootCompositionId" />
    <div class="flex w-full flex-1 flex-col gap-8 overflow-y-hidden py-1 md:flex-row md:gap-0">
      <div
        class="mx-6 max-w-2xl overflow-y-hidden bg-white shadow sm:rounded-lg md:mx-0 md:ml-8 md:w-3/5"
        :class="{ 'ring-2 ring-orange-200': model === 'fake' }"
      >
        <div class="h-full shadow sm:overflow-hidden sm:rounded-md">
          <slot />
        </div>
      </div>
      <div class="flex h-full w-full flex-col overflow-y-hidden px-6">
        <slot name="result" />
        <slot name="emptyResult" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { usePage } from '@inertiajs/inertia-vue3'
import CompositionLabel from '@/Pages/Compose/CompositionLabel.vue'
const { props: pageProps } = $(usePage<{ model?: string }>())

const { rootCompositionId, compositionLabel } = defineProps<Props>()

const model = $computed(() => pageProps.model)

interface Props {
  rootCompositionId?: string
  compositionLabel?: string
}
</script>

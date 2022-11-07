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
        <div v-if="showEmptyResultPlaceholder" class="mx-auto my-auto w-full max-w-2xl px-6">
          <div
            class="relative block rounded-lg border-2 border-dotted border-gray-300 p-12 text-center"
          >
            <icon class="mx-auto h-10 w-10 text-gray-400" icon="ph:pencil-simple-line" />
            <span class="mt-2 block text-lg font-medium text-gray-900"
              >Your composition results will appear here once available.</span
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { usePage } from '@inertiajs/inertia-vue3'
import CompositionLabel from '@/Pages/Compose/CompositionLabel.vue'
const { props: pageProps } = $(usePage<{ model?: string }>())

const { rootCompositionId, compositionLabel, showEmptyResultPlaceholder } = defineProps<Props>()

const model = $computed(() => pageProps.model)

interface Props {
  rootCompositionId?: string
  compositionLabel?: string
  showEmptyResultPlaceholder: boolean
}
</script>

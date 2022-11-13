<template>
  <AppLayout>
    <div class="h-full bg-white bg-gray-50 py-12">
      <div class="mx-auto flex h-full max-w-7xl gap-4 sm:px-6 lg:px-8">
        <div class="flex w-1/3 flex-col overflow-y-hidden">
          <div class="space-y-2 font-medium text-gray-800">
            <div class="text-lg">
              {{ composition.label }}
            </div>
            <p
              class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800"
            >
              {{ typeLabel }}
            </p>
          </div>
          <div class="pl-8 pt-8 pb-4 text-sm font-medium text-gray-800">versions</div>
          <ul role="list" class="overflow-y-auto pb-8">
            <li v-for="child in composition.children" :key="child.id">
              <CompositionShowOneVersionItem
                :composition="child"
                :root-composition="composition"
                :active="selectedComposition?.id === child.id"
                @click="showDetails(child)"
              />
            </li>
            <li>
              <CompositionShowOneVersionItem
                :composition="composition"
                :active="selectedComposition?.id === composition.id"
                @click="showDetails(composition)"
              />
            </li>
          </ul>
        </div>
        <div class="flex w-2/3 flex-col space-y-6">
          <h2 class="text-lg font-medium leading-6 text-gray-900">Results</h2>
          <template v-if="selectedComposition">
            <Component :is="resultHeader" :composition="selectedComposition" />
            <CompositionResult :result="selectedComposition.composition_result" />
          </template>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { type Component, defineAsyncComponent, PropType } from 'vue'
import CompositionShowOneVersionItem from '@/Pages/Composition/CompositionShowOneVersionItem.vue'
import CompositionResult from '@/Pages/Compose/CompositionResult.vue'
import { $computed } from 'vue/macros'
import { templates } from '@/Pages/Compose/Templates/templates'
import { CompositionTemplateType } from '@/enums'

const props = defineProps({
  composition: {
    type: Object as PropType<Composition>,
    required: true,
  },
})

let selectedComposition = $ref<Composition>(
  props.composition.children?.length ? props.composition.children[0] : props.composition
)

function showDetails(composition: Composition) {
  selectedComposition = composition
}

const typeLabel = $computed(() => {
  return templates.find((template) => template.id === props.composition.template)?.title ?? ''
})

const AmazonListingResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/AmazonListingResultHeader.vue')
)

const ResponseResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/ResponseResultHeader.vue')
)

const FreeformResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/FreeformResultHeader.vue')
)

const resultHeader = $computed<Component>(() => {
  switch (props.composition.template) {
    case CompositionTemplateType.AmazonProductListing:
      return AmazonListingResultHeader
    case CompositionTemplateType.Response:
      return ResponseResultHeader
    case CompositionTemplateType.Freeform:
      return FreeformResultHeader
  }
  return null
})
</script>

<template>
  <AppLayout :title="`Composition - ${composition.label}`">
    <div class="mx-auto h-full overflow-y-auto md:overflow-y-hidden">
      <div
        class="mx-auto flex flex-col gap-4 bg-gray-50 py-12 sm:px-6 md:h-full md:flex-row md:px-8 2xl:px-40"
      >
        <div class="flex max-w-xl flex-col px-4 md:w-1/3 md:overflow-y-hidden md:px-0">
          <div class="flex items-center justify-between">
            <div class="space-y-2 font-medium text-gray-800">
              <div class="text-lg">
                {{ label }}
              </div>
              <div
                class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800"
              >
                {{ typeLabel }}
              </div>
            </div>
            <CompositionOptions v-model:label="label" :composition="composition" />
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
        <div class="flex h-full max-w-7xl flex-col justify-between px-4 md:w-2/3 md:px-0">
          <div v-if="selectedComposition" class="flex h-full flex-col overflow-y-hidden">
            <Component :is="resultHeader" :composition="selectedComposition" class="bg-white" />
            <CompositionResult
              :result="selectedComposition.composition_result"
              class="overflow-y-auto shadow-md"
            />
          </div>
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
import { templates } from '@/Pages/Compose/templates'
import { CompositionTemplateType } from '@/enums'
import CompositionOptions from '@/Pages/Composition/CompositionOptions.vue'

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

const FreeformResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/FreeformResultHeader.vue')
)

const ResponseResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/ResponseResultHeader.vue')
)

const RewriteResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/RewriteResultHeader.vue')
)

const SummaryResultHeader = defineAsyncComponent(
  () => import('@/Pages/Composition/SummaryResultHeader.vue')
)

const resultHeader = $computed<Component>(() => {
  switch (props.composition.template) {
    case CompositionTemplateType.AmazonProductListing:
      return AmazonListingResultHeader
    case CompositionTemplateType.Freeform:
      return FreeformResultHeader
    case CompositionTemplateType.Response:
      return ResponseResultHeader
    case CompositionTemplateType.Rewrite:
      return RewriteResultHeader
    case CompositionTemplateType.Summary:
      return SummaryResultHeader
  }
  return null
})

const label = $ref<string | undefined>(props.composition.label)
</script>

<template>
  <div class="relative mb-2 pb-4">
    <span class="absolute top-3 left-1 -ml-px h-full w-0.5 bg-gray-300" aria-hidden="true" />
    <div class="relative flex items-start space-x-3">
      <div class="relative">
        <span
          class="flex h-2 w-2 items-center justify-center rounded-full border-2 border-gray-300 bg-white font-medium text-white"
        />
      </div>
      <div class="-mt-3 min-w-0 flex-1 p-2">
        <div
          class="mt-1 cursor-pointer rounded p-2"
          :class="{
            'border border-transparent hover:border-sky-100 hover:bg-sky-50': !active,
            'border border-sky-200 bg-sky-50': active,
          }"
        >
          <p class="mt-0.5 text-sm text-gray-500">
            {{ composition.created_at_short }}
          </p>
          <div class="mt-2 text-sm text-gray-700">
            <Component :is="versionInfoComponent" :composition="composition" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineAsyncComponent, PropType } from 'vue'
import { type Component } from 'vue'
import { CompositionTemplateType } from '@/enums'
import { $computed } from 'vue/macros'

const props = defineProps({
  composition: { type: Object as PropType<Composition>, required: true },
  rootComposition: { type: Object as PropType<Composition>, default: null },
  active: { type: Boolean, default: false },
})

const AmazonListingVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/AmazonListingVersionInfo.vue')
)

const ResponseVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/ResponseVersionInfo.vue')
)

const FreeformVersionInfo = defineAsyncComponent(
  () => import('@/Pages/Composition/FreeformVersionInfo.vue')
)

const versionInfoComponent = $computed<Component>(() => {
  switch (props.composition.template) {
    case CompositionTemplateType.AmazonProductListing:
      return AmazonListingVersionInfo
    case CompositionTemplateType.Response:
      return ResponseVersionInfo
    case CompositionTemplateType.Freeform:
      return FreeformVersionInfo
  }
  return null
})
</script>

<template>
  <AppLayout>
    <div class="flex flex-1 flex-col space-y-16 overflow-y-hidden py-8">
      <Component :is="templateComposer" v-if="template">
        <template #header>
          <TemplateSelector v-model="template" title="Composition Type" />
        </template>
      </Component>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import TemplateSelector from '@/Pages/Compose/TemplateSelector.vue'
import { computed, defineAsyncComponent } from 'vue'
import { CompositionTemplateType } from '@/enums'

const AmazonProductListingTemplate = defineAsyncComponent(
  () => import('@/Pages/Compose/Templates/ComposeAmazonProductListing.vue')
)
const FreeformTemplate = defineAsyncComponent(
  () => import('@/Pages/Compose/Templates/ComposeFreeform.vue')
)

const ResponseTemplate = defineAsyncComponent(
  () => import('@/Pages/Compose/Templates/ComposeResponse.vue')
)

const template: CompositionTemplateType = $ref<CompositionTemplateType>(
  CompositionTemplateType.AmazonProductListing
)

const templateComposer = computed(() => {
  switch (template) {
    case CompositionTemplateType.AmazonProductListing:
      return AmazonProductListingTemplate
    case CompositionTemplateType.Freeform:
      return FreeformTemplate
    case CompositionTemplateType.Response:
      return ResponseTemplate
  }
  throw new Error(`Invalid template ID ${template}`)
})
</script>
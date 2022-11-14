<template>
  <AppLayout title="Compose">
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
  () => import('@/Pages/Compose/AmazonProductListingCompose.vue')
)
const FreeformTemplate = defineAsyncComponent(() => import('@/Pages/Compose/FreeformCompose.vue'))

const ResponseTemplate = defineAsyncComponent(() => import('@/Pages/Compose/ResponseCompose.vue'))

const template: CompositionTemplateType = $ref<CompositionTemplateType>(
  CompositionTemplateType.Response
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

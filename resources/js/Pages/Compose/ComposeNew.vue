<template>
  <AppLayout title="Compose">
    <div class="flex flex-1 flex-col space-y-16 overflow-y-hidden py-8">
      <Component
        :is="templateComposer"
        v-if="template"
        :base-composition="composition"
        :root-composition-id="composition?.id"
      >
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
import { computed, defineAsyncComponent, PropType } from 'vue'
import { CompositionTemplateType } from '@/enums'

const props = defineProps({
  composition: { type: Object as PropType<Composition>, default: () => undefined },
})

const AmazonProductListingTemplate = defineAsyncComponent(
  () => import('@/Pages/Compose/AmazonProductListingCompose.vue')
)
const FreeformTemplate = defineAsyncComponent(() => import('@/Pages/Compose/FreeformCompose.vue'))
const ResponseTemplate = defineAsyncComponent(() => import('@/Pages/Compose/ResponseCompose.vue'))
const SummaryTemplate = defineAsyncComponent(() => import('@/Pages/Compose/SummaryCompose.vue'))

const template: CompositionTemplateType = $ref<CompositionTemplateType>(
  props.composition?.template ?? CompositionTemplateType.Response
)

const templateComposer = computed(() => {
  switch (template) {
    case CompositionTemplateType.AmazonProductListing:
      return AmazonProductListingTemplate
    case CompositionTemplateType.Freeform:
      return FreeformTemplate
    case CompositionTemplateType.Response:
      return ResponseTemplate
    case CompositionTemplateType.Summary:
      return SummaryTemplate
  }
  throw new Error(`Invalid template ID ${template}`)
})
</script>

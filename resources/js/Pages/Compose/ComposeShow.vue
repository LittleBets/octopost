<template>
  <AppLayout title="Compose">
    <div class="py-12 space-y-16 flex-1 overflow-y-hidden flex flex-col">
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

const AmazonProductListingTemplate = defineAsyncComponent(
  () => import('@/Pages/Compose/Templates/AmazonProductListing.vue')
)

const template = $ref('amazon-product-listing')

const templateComposer = computed(() => {
  switch (template) {
    case 'amazon-product-listing':
      return AmazonProductListingTemplate
  }
  throw new Error(`Invalid template ID ${template}`)
})
</script>

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

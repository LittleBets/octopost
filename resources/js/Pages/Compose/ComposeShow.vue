<template>
  <AppLayout title='Compose'>
    <template #header>
      <h2 class='font-semibold text-xl text-gray-800 leading-tight'>
        Compose
      </h2>
    </template>

    <div class='py-12 flex-1'>
      <div class='max-w-7xl mx-auto sm:px-6 lg:px-8'>
        <form @submit.prevent='submit'>
          <div class='bg-white overflow-hidden shadow-xl sm:rounded-lg'>
            <div class='shadow sm:overflow-hidden sm:rounded-md'>
              <div class='space-y-6 bg-white py-6 px-4 sm:p-6'>
                <TemplateSelector v-model='form.templateId' title='Select a Listing Type' />
                <Component :is='template' v-if='form.templateId' @update:model-value='updateModelValueHandler' />
              </div>
              <div class='bg-gray-50 px-4 py-3 text-right sm:px-6'>
                <PrimaryButton :disabled='!formValid'>
                  Compose
                </PrimaryButton>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang='ts'>
import AppLayout from '@/Layouts/AppLayout.vue'
import TemplateSelector from '@/Pages/Compose/TemplateSelector.vue'
import { computed, defineAsyncComponent, reactive } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

type Payload = Record<string, unknown>

interface Form {
  templateId?: string
  payload: Payload
  processing: boolean
}

let formValid = $ref(false)
const form = reactive<Form>({
  templateId: undefined,
  payload: {},
  processing: false,
})

const AmazonProductListingTemplate = defineAsyncComponent(() => import('@/Pages/Compose/Templates/AmazonProductListing.vue'))

const template = computed(() => {
  if (form.templateId === 'amazon-product-listing') {
    return AmazonProductListingTemplate
  }
  throw new Error('Invalid template ID ' + form.templateId)
})

function updateModelValueHandler({ payload, isValid }: { payload: Payload, isValid: boolean }) {
  formValid = isValid
  form.payload = { ...payload }
}

async function submit() {
  form.processing = true
  try {
    const response = await axios.post(route('compose.store'), {
      template: form.templateId,
      payload: form.payload,
    })
    console.log(response)
  } catch (error) {
    console.log(error)
    // todo: show an error
  } finally {
    form.processing = false
  }
}
</script>

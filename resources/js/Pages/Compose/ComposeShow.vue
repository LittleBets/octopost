<template>
  <AppLayout title='Compose'>
    <template #header>
      <h2 class='font-semibold text-xl text-gray-800 leading-tight'>
        Compose
      </h2>
    </template>

    <div class='py-12 space-y-16 flex-1 overflow-y-hidden'>
      <div class='max-w-7xl mx-auto sm:px-6 lg:px-8'>
        <form @submit.prevent='submit'>
          <div class='bg-white overflow-hidden shadow-xl sm:rounded-lg'>
            <div class='shadow sm:overflow-hidden sm:rounded-md'>
              <div class='space-y-6 bg-white py-6 px-4 sm:p-6'>
                <TemplateSelector v-model='form.template' title='Select a Composition Type' />
                <Component :is='templateComposer' v-if='form.template' @update:model-value='updateModelValueHandler' />
              </div>
              <div class='bg-gray-50 px-4 py-3 text-right sm:px-6'>
                <PrimaryButton :disabled='!canSubmit'>
                  Compose
                </PrimaryButton>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div v-if='choices.length' class='max-w-7xl mx-auto sm:px-6 lg:px-8'>
        <h3 class='mb-4 text-2xl'>Results</h3>
        <div class='space-y-4 overflow-y-auto'>
          <div
            v-for='choice in choices' :key='choice.id'
            class='bg-white overflow-hidden shadow sm:rounded-md'>
            <div class='shadow sm:overflow-hidden sm:rounded-md'>
              <div class='space-y-6 bg-white py-6 px-4 sm:p-6'>
                <p>{{ choice.text }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang='ts'>
import AppLayout from '@/Layouts/AppLayout.vue'
import TemplateSelector from '@/Pages/Compose/TemplateSelector.vue'
import { computed, defineAsyncComponent, reactive } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { usePage } from '@inertiajs/inertia-vue3'

type Payload = Record<string, unknown>

interface Form {
  template?: string
  payload: Payload
  processing: boolean
}

interface Choice {
  id: string
  composition_result_id: string
  text: string
}

const AmazonProductListingTemplate = defineAsyncComponent(() => import('@/Pages/Compose/Templates/AmazonProductListing.vue'))

const { props: pageProps } = $(usePage<{ model?: string }>())

let formValid = $ref(false)
let parentCompositionId = $ref(null)

const choices = $ref<Choice[]>([])

const form = reactive<Form>({
  template: undefined,
  payload: {},
  processing: false,
})

const templateComposer = computed(() => {
  switch (form.template) {
    case 'amazon-product-listing':
      return AmazonProductListingTemplate
  }
  throw new Error('Invalid template ID ' + form.template)
})

const canSubmit = $computed(() => {
  return !form.processing && formValid
})

function updateModelValueHandler({ payload, isValid }: { payload: Payload, isValid: boolean }) {
  formValid = isValid
  form.payload = { ...payload }
}

async function submit() {
  form.processing = true
  try {
    const { data } = await axios.post(route('compose.store'), {
      template: form.template,
      payload: form.payload,
      parent_composition_id: parentCompositionId,
      model: pageProps.model,
    })
    parentCompositionId = data.parent_composition_id
    choices.push(...data.choices)
  } catch (error) {
    console.log(error)
    // todo: show an error
  } finally {
    form.processing = false
  }
}
</script>

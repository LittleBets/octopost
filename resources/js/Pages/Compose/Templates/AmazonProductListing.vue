<template>
  <div class="sm:px-6 lg:px-8 flex-1 w-full flex overflow-y-hidden pb-1">
    <div class="w-3/5 max-w-2xl bg-white overflow-y-hidden shadow sm:rounded-lg">
      <div class="shadow sm:overflow-hidden sm:rounded-md h-full">
        <form class="flex flex-col h-full overflow-y-auto" @submit.prevent="submit">
          <div class="space-y-6 bg-white py-6 px-4 sm:p-6 flex-1 overflow-y-auto">
            <slot name="header" />
            <TextInput v-model="payload.name" label="Product Name" required />
            <Textarea
              v-model="payload.features"
              label="Product Features"
              name="product_features"
              rows="8"
              required
            />
            <ToneSelector v-model="payload.tone" />
            <AudienceSelector
              v-model="payload.audience"
              @update:checked="audienceSelectorCheckedHandler"
            />
            <LengthSelector v-model="payload.composition_length" />
            <TextInput
              v-model.number="payload.variations"
              label="Number of Variations"
              max="5"
              min="1"
              required
              type="number"
            />
          </div>
          <div
            class="flex items-center justify-between bg-gray-50 px-4 py-3 text-right sm:px-6 space-x-4"
          >
            <CompositionCostCounter :template="template" :payload="payload" />
            <div class="text-right">
              <PrimaryButton :disabled="!canSubmit"> Compose</PrimaryButton>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="w-full h-full flex flex-col">
      <div
        class="relative block mx-auto max-w-2xl w-full my-auto rounded-lg border-2 border-dotted border-gray-300 p-12 text-center"
      >
        <icon class="mx-auto h-10 w-10 text-gray-400" icon="ph:pencil-simple-line" />
        <span class="mt-2 block font-medium text-gray-900 text-lg"
          >Your listings will appear here once available.</span
        >
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import TextInput from '@/Components/TextInput.vue'
import CompositionCostCounter from '@/Components/CompositionCostCounter.vue'
import ToneSelector from '@/Pages/Compose/ToneSelector.vue'
import Textarea from '@/Components/Textarea.vue'
import { reactive } from 'vue'
import { tones } from '@/Pages/Compose/Templates/templates'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import LengthSelector from '@/Pages/Compose/LengthSelector.vue'

const { props: pageProps } = $(usePage<{ model?: string }>())

const { initial } = defineProps<Props>()

const template = 'amazon-product-listing'
let parentCompositionId = $ref()
const choices = $ref<Choice[]>([])

const payload = reactive<Fields>({
  name: initial?.payload?.name ?? '',
  tone: initial?.payload?.tone ?? tones[0].id,
  features: initial?.payload?.features ?? '',
  variations: initial?.payload?.variations ?? 1,
  audience: initial?.payload?.audience ?? '',
  composition_length: initial?.payload?.composition_length ?? 'short',
})

const isValid = $computed<boolean>(() => {
  return payload.name.trim() !== '' && payload.features.trim() !== ''
})

let processing = $ref(false)
const canSubmit = $computed(() => {
  return !processing && isValid
})

async function submit() {
  processing = true
  try {
    const { data } = await axios.post(route('compose.store'), {
      template,
      payload,
      parent_composition_id: parentCompositionId,
      model: pageProps.model,
    })
    parentCompositionId = data.parent_composition_id
    choices.push(...data.choices)
  } catch (error) {
    console.log(error)
    // todo: show an error
  } finally {
    processing = false
  }
}

function audienceSelectorCheckedHandler(state: boolean) {
  if (!state) {
    payload.audience = undefined
  }
}

interface Props {
  initial?: { payload: Fields; isValid: boolean }
}

interface Choice {
  id: string
  composition_result_id: string
  text: string
}

interface Fields {
  name: string
  tone: string
  features: string
  variations: number
  audience?: string
  model?: string
  composition_length: string
}
</script>

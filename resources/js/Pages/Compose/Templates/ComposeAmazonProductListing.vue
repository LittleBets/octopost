<template>
  <ComposerShell :root-composition-id="rootCompositionId" :composition-label="compositionLabel">
    <form class="flex h-full flex-col overflow-y-auto" @submit.prevent="submit">
      <div class="flex h-full flex-1 flex-col space-y-6 overflow-y-auto bg-white py-6 px-4 sm:p-6">
        <slot name="header" />
        <TextInput ref="productNameRef" v-model="payloadForm.name" label="Product Name" required />
        <Textarea
          v-model="payloadForm.features"
          label="Product Features"
          name="product_features"
          :rows="8"
          required
        />
        <ToneSelector v-model="payloadForm.tone" />
        <AudienceSelector
          v-model="payloadForm.audience"
          v-model:checked="audienceSelectorChecked"
        />
        <LengthSelector v-model="payloadForm.composition_length" />
        <TextInput
          v-model.number="payloadForm.variations"
          label="Number of Variations"
          :max="5"
          :min="1"
          required
          type="number"
        />
      </div>
      <div
        class="flex items-center justify-between space-x-4 bg-gray-50 px-4 py-3 text-right sm:px-6"
      >
        <CompositionCostCounter :template="template" :payload="payloadForm" />

        <div class="inline-flex items-center gap-8 text-right">
          <LinkButton class="text-sm" label="Start Over" @click="startNewHandler" />
          <PrimaryButton :disabled="!canSubmit"
            >{{ rootCompositionId == null ? 'Compose' : 'Recompose' }}
          </PrimaryButton>
        </div>
      </div>
    </form>
    <template v-if="compositionResult" #result>
      <AmazonProductListingResult :result="compositionResult" :version="compositionVersion">
        <template #footer>
          <ResultFooter :composition-version="compositionVersion" />
        </template>
      </AmazonProductListingResult>
    </template>
    <template v-else #emptyResult>
      <EmptyResult />
    </template>
  </ComposerShell>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { nextTick } from 'vue'
import TextInput from '@/Components/TextInput.vue'
import CompositionCostCounter from '@/Components/CompositionCostCounter.vue'
import ToneSelector from '@/Pages/Compose/ToneSelector.vue'
import Textarea from '@/Components/Textarea.vue'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import LengthSelector from '@/Pages/Compose/LengthSelector.vue'
import AmazonProductListingResult from '@/Pages/Compose/CompositionResult.vue'
import LinkButton from '@/Components/LinkButton.vue'
import ResultFooter from '@/Pages/Compose/ResultFooter.vue'
import ComposerShell from '@/Pages/Compose/ComposerShell.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'
import { tones } from '@/Pages/Compose/Templates/templates'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

const { props: pageProps } = usePage<{ model?: string }>()

const { initial } = defineProps<Props>()

const template = 'amazon-product-listing'

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(undefined)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = useForm<Fields>({
  name: initial?.payload?.name ?? '',
  tone: initial?.payload?.tone ?? tones[0].id,
  features: initial?.payload?.features ?? '',
  variations: initial?.payload?.variations ?? 1,
  audience: initial?.payload?.audience ?? undefined,
  composition_length: initial?.payload?.composition_length ?? 'short',
})
let audienceSelectorChecked = $ref(false)

const isValid = $computed<boolean>(() => {
  return payloadForm.name.trim() !== '' && payloadForm.features.trim() !== ''
})

let processing = $ref(false)
const canSubmit = $computed(() => {
  return !processing && isValid
})

async function submit() {
  processing = true
  try {
    payloadForm.transform((data) => {
      return {
        ...data,
        audience: audienceSelectorChecked ? data.audience : undefined,
      }
    })
    const { data } = await axios.post(route('composition.store'), {
      template,
      payload: payloadForm,
      root_composition_id: rootCompositionId,
      model,
    })
    compositionResult = data.result
    rootCompositionId = data.root_composition_id
    compositionVersion = data.composition_version
    if (compositionLabel == null) {
      compositionLabel = data.composition_label
    }
  } catch (error) {
    console.log(error)
    // todo: show an error
  } finally {
    processing = false
  }
}

function startNewHandler() {
  resetPayloadForm()
  resetResult()
  compositionLabel = undefined
  setFocus()
}

function resetPayloadForm() {
  payloadForm.reset()
  audienceSelectorChecked = false
}

function resetResult() {
  compositionResult = undefined
  rootCompositionId = undefined
  compositionVersion = undefined
}

let compositionLabel = $ref<string | undefined>()

const productNameRef = $ref<HTMLElement>()
function setFocus() {
  nextTick(() => {
    productNameRef?.focus()
  })
}

setFocus()

interface Props {
  initial?: { payload: Fields; isValid: boolean }
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

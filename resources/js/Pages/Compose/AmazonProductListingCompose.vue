<template>
  <ComposerShell
    :root-composition-id="rootCompositionId"
    :composition-label="compositionLabel"
    :can-submit="canSubmit"
    :payload="payloadForm"
    :template-type="CompositionTemplateType.AmazonProductListing"
    @submit="submit"
    @start-over="startNewHandler"
  >
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
    <AudienceSelector v-model="payloadForm.audience" v-model:checked="audienceSelectorChecked" />
    <LengthSelector v-model="payloadForm.composition_length" />
    <TextInput
      v-model.number="payloadForm.variations"
      label="Number of Variations"
      :max="5"
      :min="1"
      required
      type="number"
    />
    <template v-if="compositionResult" #result>
      <CompositionResult :result="compositionResult" :version="compositionVersion">
        <template #footer>
          <ResultFooter
            :composition-version="compositionVersion"
            :composition-id="rootCompositionId"
          />
        </template>
      </CompositionResult>
    </template>
    <template v-else #emptyResult>
      <EmptyResult />
    </template>
  </ComposerShell>
</template>

<script lang="ts" setup>
import { $computed } from 'vue/macros'
import { nextTick, reactive } from 'vue'
import TextInput from '@/Components/TextInput.vue'
import ToneSelector from '@/Pages/Compose/ToneSelector.vue'
import Textarea from '@/Components/Textarea.vue'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'
import LengthSelector from '@/Pages/Compose/LengthSelector.vue'
import CompositionResult from '@/Pages/Compose/CompositionResult.vue'
import ResultFooter from '@/Pages/Compose/ResultFooter.vue'
import ComposerShell from '@/Pages/Compose/ComposerShell.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'
import { tones } from '@/Pages/Compose/templates'
import { usePage } from '@inertiajs/inertia-vue3'
import { CompositionTemplateType } from '@/enums'

const { props: pageProps } = usePage<{ model?: string }>()

const { initial } = defineProps<Props>()

const template = 'amazon-product-listing'

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(undefined)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = reactive<Fields>({
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
    const { data } = await axios.post(route('compositions.store'), {
      template,
      payload: {
        ...payloadForm,
        audience: audienceSelectorChecked ? payloadForm.audience : undefined,
      },
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
  payloadForm.name = ''
  payloadForm.tone = tones[0].id
  payloadForm.features = ''
  payloadForm.variations = 1
  payloadForm.audience = undefined
  payloadForm.composition_length = 'short'
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

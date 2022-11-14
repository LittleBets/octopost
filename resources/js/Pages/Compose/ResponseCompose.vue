<template>
  <ComposerShell
    :root-composition-id="rootCompositionId"
    :composition-label="compositionLabel"
    :can-submit="canSubmit"
    :payload="payloadForm"
    :template-type="CompositionTemplateType.Response"
    @submit="submit"
    @start-over="startNewHandler"
  >
    <slot name="header" />
    <ResponseTypeSelector v-model="payloadForm.response_type" />
    <Textarea
      ref="messageRef"
      v-model="payloadForm.message"
      label="Message to Reply"
      name="message"
      :rows="12"
      required
    />
    <ToneSelector v-model="payloadForm.tone" />
    <AudienceSelector
      v-model="payloadForm.audience"
      v-model:checked="audienceSelectorChecked"
      title="Address this response to"
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
import { responseTypes, tones } from '@/Pages/Compose/templates'
import { usePage } from '@inertiajs/inertia-vue3'
import ResponseTypeSelector from '@/Pages/Compose/ResponseTypeSelector.vue'
import { CompositionTemplateType } from '@/enums'

const { props: pageProps } = usePage<{ model?: string }>()

const { initial } = defineProps<Props>()

const template = 'response'

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(undefined)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = reactive<Fields>({
  tone: initial?.payload?.tone ?? tones[0].id,
  response_type: initial?.payload?.response_type ?? responseTypes[0].id,
  message: initial?.payload?.message ?? '',
  variations: initial?.payload?.variations ?? 1,
  audience: initial?.payload?.audience ?? undefined,
  composition_length: initial?.payload?.composition_length ?? 'short',
})
let audienceSelectorChecked = $ref(false)

const isValid = $computed<boolean>(() => {
  return payloadForm.message.trim() !== ''
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
  payloadForm.tone = tones[0].id
  payloadForm.response_type = responseTypes[0].id
  payloadForm.message = ''
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

const messageRef = $ref<HTMLElement>()

function setFocus() {
  nextTick(() => {
    messageRef?.focus()
  })
}

setFocus()

interface Props {
  initial?: { payload: Fields; isValid: boolean }
}

interface Fields {
  tone: string
  response_type: string
  message: string
  variations: number
  audience?: string
  composition_length: string
}
</script>
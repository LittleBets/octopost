<template>
  <ComposerShell
    :root-composition-id="rootCompositionId"
    :composition-label="compositionLabel"
    :can-submit="canSubmit"
    :payload="payloadForm"
    :template-type="CompositionTemplateType.Summary"
    @submit="submit"
    @start-over="startNewHandler"
  >
    <slot name="header" />
    <Textarea
      ref="textRef"
      v-model="payloadForm.text"
      label="Text to Summarize"
      name="text"
      :rows="20"
      required
    />
    <AudienceSelector
      v-model:checked="audienceSelectorChecked"
      title="Target Reader"
      :model-value="baseComposition?.payload?.audience ?? audiences[0].id"
      @update:model-value="(val) => (payloadForm.audience = val)"
    />
    <LengthSelector v-model="payloadForm.composition_length" />
    <template v-if="compositionResult" #result>
      <CompositionResult :result="compositionResult" :version="compositionVersion" class="h-full">
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
import { nextTick, PropType, reactive } from 'vue'
import Textarea from '@/Components/Textarea.vue'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'
import LengthSelector from '@/Pages/Compose/LengthSelector.vue'
import CompositionResult from '@/Pages/Compose/CompositionResult.vue'
import ResultFooter from '@/Pages/Compose/ResultFooter.vue'
import ComposerShell from '@/Pages/Compose/ComposerShell.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'
import { audiences } from '@/Pages/Compose/templates'
import { usePage } from '@inertiajs/inertia-vue3'
import { CompositionTemplateType } from '@/enums'

const { props: pageProps } = usePage<{ model?: string }>()

const props = defineProps({
  baseComposition: {
    type: Object as PropType<Composition<SummaryCompositionPayload>>,
    default: () => undefined,
  },
  rootCompositionId: { type: String, default: undefined },
})

const template = CompositionTemplateType.Summary

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(props.rootCompositionId)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = reactive<SummaryCompositionPayload>({
  text: props.baseComposition?.payload?.text ?? '',
  audience: props.baseComposition?.payload?.audience ?? audiences[0].id,
  composition_length: String(props.baseComposition?.payload?.composition_length ?? 'medium'),
  variations: props.baseComposition?.payload?.variations ?? 1,
})
let audienceSelectorChecked = $ref(false)

const isValid = $computed<boolean>(() => {
  return payloadForm.text.trim() !== ''
})

let processing = $ref(false)
const canSubmit = $computed(() => {
  return !processing && isValid
})

async function submit() {
  try {
    processing = true
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
  payloadForm.text = ''
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

let compositionLabel = $ref<string | undefined>(props.baseComposition?.label)

const textRef = $ref<HTMLElement>()

function setFocus() {
  nextTick(() => {
    textRef?.focus()
  })
}

setFocus()
</script>

<template>
  <ComposerShell
    :root-composition-id="rootCompositionId"
    :composition-label="compositionLabel"
    :can-submit="canSubmit"
    :payload="payloadForm"
    :template-type="CompositionTemplateType.Freeform"
    @submit="submit"
    @start-over="startNewHandler"
  >
    <slot name="header" />
    <Textarea
      ref="textRef"
      v-model="payloadForm.text"
      label="Original Text"
      name="text"
      :rows="20"
      required
    />
    <RewriteTypeSelector
      :model-value="baseComposition?.payload?.rewrite_type ?? rewriteTypes[0].id"
      @update:model-value="(val) => (payloadForm.rewrite_type = val)"
    />
    <div class="relative">
      <ToneSelector
        v-model:checked="toneSelectorChecked"
        :model-value="baseComposition?.payload?.tone ?? tones[0].id"
        @update:model-value="(val) => (payloadForm.tone = val)"
      />
      <div v-if="isCorrectGrammarType" class="absolute inset-0 w-full bg-white/70" />
    </div>
    <div class="relative">
      <AudienceSelector
        v-model:checked="audienceSelectorChecked"
        title="Target Audience"
        :model-value="baseComposition?.payload?.audience ?? audiences[0].id"
        @update:model-value="(val) => (payloadForm.audience = val)"
      />
      <div v-if="isCorrectGrammarType" class="absolute inset-0 w-full bg-white/70" />
    </div>

    <TextInput
      v-model.number="payloadForm.composition_length"
      label="Max Number of Words"
      :min="20"
      required
      type="number"
    />
    <VariationInput v-model="payloadForm.variations" />
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
import { nextTick, PropType, reactive, watchEffect } from 'vue'
import TextInput from '@/Components/TextInput.vue'
import Textarea from '@/Components/Textarea.vue'
import CompositionResult from '@/Pages/Compose/CompositionResult.vue'
import ResultFooter from '@/Pages/Compose/ResultFooter.vue'
import ComposerShell from '@/Pages/Compose/ComposerShell.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { CompositionTemplateType } from '@/enums'
import VariationInput from '@/Pages/Compose/VariationInput.vue'
import ToneSelector from '@/Pages/Compose/ToneSelector.vue'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'
import { audiences, rewriteTypes, tones } from '@/Pages/Compose/templates'
import RewriteTypeSelector from '@/Pages/Compose/RewriteTypeSelector.vue'

const { props: pageProps } = usePage<{ model?: string }>()

const props = defineProps({
  baseComposition: {
    type: Object as PropType<Composition<RewriteCompositionPayload>>,
    default: () => undefined,
  },
  rootCompositionId: { type: String, default: undefined },
})
const template = CompositionTemplateType.Rewrite

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(props.rootCompositionId)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = reactive<RewriteCompositionPayload>({
  variations: props.baseComposition?.payload?.variations ?? 1,
  composition_length: Number(props.baseComposition?.payload?.composition_length ?? 600),
  text: props.baseComposition?.payload?.text ?? '',
  audience: props.baseComposition?.payload?.audience ?? audiences[0].id,
  tone: props.baseComposition?.payload?.tone ?? tones[0].id,
  rewrite_type: props.baseComposition?.payload?.rewrite_type ?? rewriteTypes[0].id,
})

let audienceSelectorChecked = $ref(false)
let toneSelectorChecked = $ref(false)
const isCorrectGrammarType = $computed(() => payloadForm.rewrite_type === 'correct')

watchEffect(() => {
  if (isCorrectGrammarType) {
    audienceSelectorChecked = false
    toneSelectorChecked = false
  }
})

const isValid = $computed<boolean>(() => {
  return payloadForm.text.trim() !== ''
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
        tone: toneSelectorChecked ? payloadForm.tone : undefined,
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
  payloadForm.variations = 1
  payloadForm.composition_length = 600
  payloadForm.text = ''
  payloadForm.audience = undefined
  audienceSelectorChecked = false
  toneSelectorChecked = false
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

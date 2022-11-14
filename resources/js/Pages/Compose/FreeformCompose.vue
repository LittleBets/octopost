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
      ref="promptRef"
      v-model="payloadForm.input_prompt"
      label="Prompt"
      name="prompt"
      :rows="20"
      placeholder="e.g. In a friendly tone, write a blog post convincing old ladies to get a cat as their best friend.
Include keywords cute, cuddly, fury, kittens"
      required
    />
    <TextInput
      v-model.number="payloadForm.composition_length"
      label="Max Number of Tokens"
      :min="20"
      required
      type="number"
    />
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
import Textarea from '@/Components/Textarea.vue'
import CompositionResult from '@/Pages/Compose/CompositionResult.vue'
import ResultFooter from '@/Pages/Compose/ResultFooter.vue'
import ComposerShell from '@/Pages/Compose/ComposerShell.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { CompositionTemplateType } from '@/enums'

const { props: pageProps } = usePage<{ model?: string }>()

const { initial } = defineProps<Props>()

const template = 'freeform'

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(undefined)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = reactive<Fields>({
  variations: initial?.payload?.variations ?? 1,
  composition_length: initial?.payload?.composition_length ?? 500,
  input_prompt: initial?.payload?.input_prompt ?? '',
})

const isValid = $computed<boolean>(() => {
  return payloadForm.input_prompt.trim() !== ''
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
  payloadForm.variations = 1
  payloadForm.composition_length = 500
  payloadForm.input_prompt = ''
}

function resetResult() {
  compositionResult = undefined
  rootCompositionId = undefined
  compositionVersion = undefined
}

let compositionLabel = $ref<string | undefined>()

const promptRef = $ref<HTMLElement>()

function setFocus() {
  nextTick(() => {
    promptRef?.focus()
  })
}

setFocus()

interface Props {
  initial?: { payload: Fields; isValid: boolean }
}

interface Fields {
  input_prompt: string
  variations: number
  model?: string
  composition_length: number
}
</script>

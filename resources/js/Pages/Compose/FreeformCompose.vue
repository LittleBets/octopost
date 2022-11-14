<template>
  <ComposerShell :root-composition-id="rootCompositionId" :composition-label="compositionLabel">
    <form class="flex h-full flex-col overflow-y-auto" @submit.prevent="submit">
      <div class="flex flex-1 flex-col space-y-6 overflow-y-auto bg-white py-6 px-4 sm:p-6">
        <slot name="header" />
        <Textarea
          ref="promptRef"
          v-model="payloadForm.input_prompt"
          label="Prompt"
          name="prompt"
          :rows="20"
          placeholder="e.g. In a friendly and witty tone, write a blog post convincing old ladies to get a cat as their best friend.
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
import { nextTick } from 'vue'
import TextInput from '@/Components/TextInput.vue'
import CompositionCostCounter from '@/Components/CompositionCostCounter.vue'
import Textarea from '@/Components/Textarea.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import CompositionResult from '@/Pages/Compose/CompositionResult.vue'
import LinkButton from '@/Components/LinkButton.vue'
import ResultFooter from '@/Pages/Compose/ResultFooter.vue'
import ComposerShell from '@/Pages/Compose/ComposerShell.vue'
import EmptyResult from '@/Pages/Compose/EmptyResult.vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

const { props: pageProps } = usePage<{ model?: string }>()

const { initial } = defineProps<Props>()

const template = 'freeform'

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(undefined)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.value.model)

const payloadForm = useForm<Fields>({
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
  payloadForm.reset()
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

<template>
  <div class="flex w-full flex-1 flex-col overflow-y-hidden">
    <div class="mx-8 mb-4 text-xl font-medium text-gray-500">
      {{ compositionLabel ?? `&nbsp;` }}
    </div>
    <div class="flex w-full flex-1 flex-col gap-8 overflow-y-hidden py-1 md:flex-row md:gap-0">
      <div
        class="mx-6 max-w-2xl overflow-y-hidden bg-white shadow sm:rounded-lg md:mx-0 md:ml-8 md:w-3/5"
        :class="{ 'ring-2 ring-orange-200': model === 'fake' }"
      >
        <div class="h-full shadow sm:overflow-hidden sm:rounded-md">
          <form class="flex h-full flex-col overflow-y-auto" @submit.prevent="submit">
            <div class="flex-1 space-y-6 overflow-y-auto bg-white py-6 px-4 sm:p-6">
              <slot name="header" />
              <TextInput v-model="payloadForm.name" label="Product Name" required />
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
                <LinkButton label="Start New" @click="startNewHandler" />
                <PrimaryButton :disabled="!canSubmit">{{
                  rootCompositionId == null ? 'Compose' : 'Recompose'
                }}</PrimaryButton>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="flex h-full w-full flex-col overflow-y-hidden px-6">
        <AmazonProductListingResult
          v-if="compositionResult"
          :result="compositionResult"
          :version="compositionVersion"
        >
          <template #footer>
            <div class="w-full bg-gray-100 leading-relaxed text-gray-600">
              <p>
                All your composition iterations are saved as versions. This is version
                {{ compositionVersion }}. <LinkButton label="Check out" underline /> all the
                versions.
              </p>
              <p>
                <LinkButton label="Label" underline @click="showingLabelDialog = true" /> this
                composition to make it easier to identify later. You may also
                <LinkButton label="start" underline @click="startNewHandler" /> a new composition.
              </p>
            </div>
          </template>
        </AmazonProductListingResult>
        <div v-else class="mx-auto my-auto w-full max-w-2xl px-6">
          <div
            class="relative block rounded-lg border-2 border-dotted border-gray-300 p-12 text-center"
          >
            <icon class="mx-auto h-10 w-10 text-gray-400" icon="ph:pencil-simple-line" />
            <span class="mt-2 block text-lg font-medium text-gray-900"
              >Your listings will appear here once available.</span
            >
          </div>
        </div>
      </div>
      <CompositionLabelUpdateModal
        v-if="rootCompositionId"
        v-model="compositionLabel"
        :show="showingLabelDialog"
        :composition-id="rootCompositionId"
        @close="showingLabelDialog = false"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import TextInput from '@/Components/TextInput.vue'
import CompositionCostCounter from '@/Components/CompositionCostCounter.vue'
import ToneSelector from '@/Pages/Compose/ToneSelector.vue'
import Textarea from '@/Components/Textarea.vue'
import AudienceSelector from '@/Pages/Compose/AudienceSelector.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import LengthSelector from '@/Pages/Compose/LengthSelector.vue'
import AmazonProductListingResult from '@/Pages/Compose/Templates/AmazonProductListingResult.vue'
import LinkButton from '@/Components/LinkButton.vue'
import { tones } from '@/Pages/Compose/Templates/templates'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { $computed } from 'vue/macros'
import CompositionLabelUpdateModal from '@/Pages/Compose/CompositionLabelUpdateModal.vue'

const { props: pageProps } = $(usePage<{ model?: string }>())

const { initial } = defineProps<Props>()

const template = 'amazon-product-listing'

let compositionResult = $ref<CompositionResult | undefined>(undefined)
let rootCompositionId = $ref<string | undefined>(undefined)
let compositionVersion = $ref<number | undefined>(undefined)
const model = $computed(() => pageProps.model)

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

let compositionLabel = $ref()
const showingLabelDialog = $ref(false)

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

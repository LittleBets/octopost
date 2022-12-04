<template>
  <div
    ref="shortcutActiveContainer"
    class="group mx-2 bg-white p-4 ring-1 ring-transparent transition duration-150 ease-in-out"
    :class="{ 'ring-green-500/20': highlightChoice }"
  >
    <div class="flex flex-col items-start">
      <p class="mt-1 whitespace-pre-line leading-7 text-gray-900">
        <span>
          {{ text }}
        </span>
        <span
          v-if="finishedBecauseOfLength"
          v-tippy="
            'Octopost might have stopped generating more text because of the output length you selected. Try increasing the length to get more text.'
          "
          class="mx-2 inline-block rounded bg-red-100 py-0.5 px-1 text-sm tracking-wider text-red-900"
          >...</span
        >
      </p>
      <div
        class="mt-4 flex w-full items-center justify-between gap-4 opacity-25 transition duration-300 ease-in-out hover:transition-all group-hover:opacity-100"
      >
        <LinkButton v-tippy="'Delete &nbsp;&nbsp;&nbsp;D'" @click="confirmingDeletion = true">
          <icon icon="ph:trash" class="h-5 w-auto text-gray-500 hover:text-red-500" />
        </LinkButton>
        <div class="flex items-center justify-end gap-4">
          <LinkButton v-tippy="'Edit &nbsp;&nbsp;&nbsp;E'" @click="edit">
            <icon icon="ph:pencil-simple" class="h-5 w-auto" />
          </LinkButton>
          <LinkButton
            v-if="isClipboardSupported"
            v-tippy="'Copy to Clipboard &nbsp;&nbsp;&nbsp;C'"
            @click="copyToClipboard"
          >
            <icon icon="ph:clipboard" class="h-5 w-auto" />
          </LinkButton>
        </div>
      </div>
    </div>
    <ResultChoiceUpdateModal
      v-if="choiceToEdit"
      :model-value="choiceToEdit"
      :show="showingResultTextEditDialog"
      @close="showingResultTextEditDialog = false"
      @update:model-value="resultChoiceUpdatedHandler"
    />
    <ConfirmationModal :show="confirmingDeletion" @close="confirmingDeletion = false">
      <template #title> Delete Result </template>
      <template #content>
        <div class="font-semibold">Are you sure you want to delete this result?</div>
        <div class="my-4 rounded bg-gray-100 py-2 px-4 leading-relaxed shadow-inner">
          {{ choice.text }}
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="confirmingDeletion = false"> Cancel </SecondaryButton>
        <DangerButton class="ml-3" :disabled="deleteForm.processing" @click="deleteResult">
          Yes, Delete
        </DangerButton>
      </template>
    </ConfirmationModal>
  </div>
</template>

<script setup lang="ts">
import { PropType, ref } from 'vue'
import LinkButton from '@/Components/LinkButton.vue'
import ResultChoiceUpdateModal from '@/Pages/Compose/ResultChoiceUpdateModal.vue'
import { useClipboard, useMagicKeys, whenever } from '@vueuse/core'
import { and } from '@vueuse/math'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { useIsMouseInside } from '@/compositions/useIsMouseInside'
import { useForm } from '@inertiajs/inertia-vue3'
import { $computed } from 'vue/macros'

const { copy, isSupported: isClipboardSupported } = useClipboard()

const props = defineProps({
  choice: { type: Object as PropType<CompositionResultChoice>, required: true },
})

const emit = defineEmits<{
  (e: 'deleted', choiceId: string): void
}>()

const finishedBecauseOfLength = $computed(() => props.choice?.extras?.finish_reason === 'length')

let choiceToEdit = $ref<CompositionResultChoice>()

function copyToClipboard() {
  copy(text)
}

let highlightChoice = $ref(false)
let text = $ref(props.choice.text)

function resultChoiceUpdatedHandler(choice: CompositionResultChoice) {
  text = choice.text
  highlightChoice = true
  setTimeout(() => {
    highlightChoice = false
  }, 1000)
}

let showingResultTextEditDialog = $ref(false)

function edit() {
  choiceToEdit = props.choice
  showingResultTextEditDialog = true
}

let confirmingDeletion = $ref(false)
const deleteForm = useForm({})

async function deleteResult() {
  deleteForm.delete(route('compositions.results.choices.destroy', props.choice.id), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      emit('deleted', props.choice.id)
    },
    onError: (error) => {
      console.log(error)
    },
    onFinish: () => {
      confirmingDeletion = false
    },
  })
}

const shortcutActiveContainer = ref<HTMLElement | null>(null)
const { isInside } = useIsMouseInside(shortcutActiveContainer)

const { c, d, e } = useMagicKeys()

whenever(and(c, isInside), () => {
  copyToClipboard()
})

whenever(and(d, isInside), () => {
  confirmingDeletion = true
})

whenever(and(e, isInside), () => {
  edit()
})
</script>

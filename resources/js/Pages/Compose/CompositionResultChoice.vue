<template>
  <div
    ref="containerRef"
    class="group mx-auto rounded bg-white p-4 shadow-sm hover:bg-gray-50 lg:max-w-4xl"
  >
    <div class="flex flex-col items-start">
      <p class="mt-1 text-base leading-8 text-slate-700">
        {{ text }}
      </p>
      <div
        class="mt-4 flex w-full items-center justify-between gap-4 opacity-25 transition duration-300 ease-in-out hover:transition-all group-hover:opacity-100"
      >
        <div class="flex items-center">
          <LinkButton v-tippy="'Delete &nbsp;&nbsp;&nbsp;D'" @click="confirmingDeletion = true">
            <icon icon="ph:trash" class="h-5 w-auto group-hover:text-red-500" />
          </LinkButton>
        </div>
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
        <div class="my-4 rounded bg-gray-100 py-2 px-4 leading-relaxed">{{ choice.text }}</div>
      </template>

      <template #footer>
        <SecondaryButton @click="confirmingDeletion = false"> Cancel </SecondaryButton>
        <DangerButton class="ml-3" :disabled="isDeleting" @click="deleteResult">
          Delete Result
        </DangerButton>
      </template>
    </ConfirmationModal>
  </div>
</template>

<script setup lang="ts">
import { nextTick, PropType, ref } from 'vue'
import LinkButton from '@/Components/LinkButton.vue'
import ResultChoiceUpdateModal from '@/Pages/Compose/ResultChoiceUpdateModal.vue'
import { useClipboard, useMagicKeys, whenever } from '@vueuse/core'
import { and } from '@vueuse/math'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { useIsMouseInside } from '@/compositions/useIsMouseInside'

const { copy, isSupported: isClipboardSupported } = useClipboard()

const props = defineProps({
  choice: { type: Object as PropType<CompositionResultChoice>, required: true },
})

const emit = defineEmits<{
  (e: 'deleted', choiceId: string): void
}>()

let choiceToEdit = $ref<CompositionResultChoice>()

function copyToClipboard() {
  copy(props.choice.text)
}

let text = $ref(props.choice.text)

function resultChoiceUpdatedHandler(choice: CompositionResultChoice) {
  text = choice.text
}

let showingResultTextEditDialog = $ref(false)

function edit() {
  choiceToEdit = props.choice
  showingResultTextEditDialog = true
}

let isDeleting = $ref(false)
let confirmingDeletion = $ref(false)

async function deleteResult() {
  try {
    isDeleting = true
    await axios.delete(route('composition-result-choice.destroy', props.choice.id))
    confirmingDeletion = false
    await nextTick()
    emit('deleted', props.choice.id)
  } catch (error) {
    console.error(error)
    // todo: show error
  } finally {
    isDeleting = false
  }
}

const containerRef = ref<HTMLElement | null>(null)
const { isInside } = useIsMouseInside(containerRef)

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

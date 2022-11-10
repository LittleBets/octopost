<template>
  <DialogModal :show="openDialog">
    <template #title>
      <p>Update Result</p>
    </template>
    <template #content>
      <Textarea ref="resultTextareaRef" v-model="text" :rows="20" />
    </template>
    <template #footer>
      <SecondaryButton @click="closeDialog"> Cancel</SecondaryButton>
      <Button class="ml-3" :disabled="!canUpdate" @click="save"> Save</Button>
    </template>
  </DialogModal>
</template>

<script lang="ts" setup>
import Button from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DialogModal from '@/Components/DialogModal.vue'
import { $computed } from 'vue/macros'
import { nextTick, watchEffect, PropType } from 'vue'
import Textarea from '@/Components/Textarea.vue'

const props = defineProps({
  show: { type: Boolean, required: true },
  modelValue: { type: Object as PropType<CompositionResultChoice>, required: true },
})
const emit = defineEmits<Emits>()

const text = $ref(props.modelValue.text)

const canUpdate = $computed(() => text.trim().length)

const resultTextareaRef = $ref<HTMLElement>()

watchEffect(() => {
  if (props.show) {
    resultTextareaRef?.focus()
  }
})

let openDialog = $computed({
  get() {
    return props.show
  },
  set() {
    emit('close')
  },
})

function closeDialog() {
  openDialog = false
}

async function save() {
  try {
    const { data } = await axios.patch(
      route('composition-result-choice.update', props.modelValue.id),
      {
        text: text.trim(),
      }
    )
    closeDialog()
    await nextTick()
    emit('update:modelValue', {
      ...props.modelValue,
      text: data.text,
    })
  } catch (error) {
    console.error(error)
  }
}

interface Emits {
  (e: 'close'): void
  (e: 'update:modelValue', choice: CompositionResultChoice): void
}
</script>
